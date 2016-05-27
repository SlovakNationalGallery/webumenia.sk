<?php

namespace App\Http\Controllers;

use App\Authority;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Link;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\SpiceHarvesterRecord;
use Illuminate\Support\Facades\App;

class AuthorityController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $authorities = Authority::orderBy('updated_at', 'DESC')->paginate(100);
        return view('authorities.index', array('authorities' => $authorities));
    }

    /**
     * Find and display a listing
     *
     * @return Response
     */
    public function search()
    {

        $search = Input::get('search');
        $results = Authority::where('name', 'LIKE', '%'.$search.'%')->orWhere('id', 'LIKE', '%'.$search.'%')->orderBy('view_count', '')->paginate(20);

        return view('authorities.index', array('authorities' => $results, 'search' => $search));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $prefix = 'TMP.';  // TMP = temporary
        $pocet = Authority::where('id', 'LIKE', $prefix.'%')->count();
        $new_id = $prefix . ($pocet+1);
        return view('authorities.form', array('new_id'=>$new_id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();

        $rules = Authority::$rules;
        $rules['primary_image'] = 'required|image';

        $v = Validator::make($input, $rules);

        if ($v->passes()) {

            $input = array_filter($input, 'strlen');
            $authority = new Authority;
            $authority->fill($input);
            $authority->save();

            if (Input::hasFile('primary_image')) {
                $this->uploadImage($authority);
            }

            return Redirect::route('authority.index');
        }
        return Redirect::back()->withInput()->withErrors($v);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $authority = Authority::find($id);
        return view('authorities.show')->with('authority', $authority);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $authority = Authority::find($id);

        if (is_null($authority)) {
            return Redirect::route('authority.index');
        }

        return view('authorities.form')->with('authority', $authority);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $v = Validator::make(Input::all(), Authority::$rules);
        if ($v->passes()) {
            $input = array_except(Input::all(), array('_method'));
            // $input = array_filter($input); //, 'strlen'
            $input = array_map(function ($e) {
                return $e ?: null;

            }, Input::all());

            $authority = Authority::find($id);
            $authority->fill($input);
            $authority->save();
            // dd(Input::get('links'));
            foreach (Input::get('links') as $link) {
                $validation = Validator::make($link, Link::$rules);
                if ($validation->passes()) {
                    if (empty($link['label'])) {
                        $link['label'] = Link::parse($link['url']);
                    }

                    if (!empty($link['id'])) {
                        $new_link = Link::updateOrCreate(['id'=>$link['id']], $link);
                    } else {
                        $new_link = new Link($link);
                        $new_link->save();
                        $authority->links()->save($new_link);
                    }
                }
            }

            // ulozit primarny obrazok. do databazy netreba ukladat. nazov=id
            if (Input::has('primary_image')) {
                $this->uploadImage($authority);
            }

            Session::flash('message', 'Autorita ' .$id. ' bola upravená');
            return Redirect::route('authority.index');
        }

        return Redirect::back()->withErrors($v);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the link
     *
     * @param  int  $id
     * @return Response
     */
    public function destroyLink($link_id)
    {
        Link::find($link_id)->delete();
        return Redirect::back()->with('message', 'Externý odkaz bol zmazaný');
    }


    public function backup()
    {
        $sqlstring = "";
        $table = 'authorities';
        $newline = "\n";

        $prefix = 'SVK:TMP.';
        $authorities = Authority::where('id', 'LIKE', $prefix.'%')->get();
        foreach ($authorities as $key => $authority) {
            $authority_data = $authority->toArray();
            
            $keys = array();
            $values = array();
            foreach ($authority_data as $key => $value) {
                $keys[] = "`" . $key . "`";
                if ($value === null) {
                    $value = "NULL";
                } elseif ($value === "" or $value === false) {
                    $value = '""';
                } elseif (!is_numeric($value)) {
                    DB::connection()->getPdo()->quote($value);
                    $value = "\"$value\"";
                }
                $values[] = $value;
            }
            $sqlstring  .= "INSERT INTO `" . $table . "` ( "
                        .  implode(", ", $keys)
                        .    " ){$newline}\tVALUES ( "
                        .  implode(", ", $values)
                        .    " );" . $newline;

        }
        $filename = date('Y-m-d-H-i').'_'.$table.'.sql';
        File::put(app_path() .'/database/backups/' . $filename, $sqlstring);
        return Redirect::back()->withMessage('Záloha ' . $filename . ' bola vytvorená.');

    }

    public function geodata()
    {
        $authorities = Authority::where('place', '!=', '')->get();
        $i = 0;
        foreach ($authorities as $authority) {
            if (!empty($authority->place) && (empty($authority->lat))) {
                $geoname = Ipalaus\Geonames\Eloquent\Name::where('name', 'like', $authority->place)->orderBy('population', 'desc')->first();
                //ak nevratil, skusim podla alternate_names
                if (empty($geoname)) {
                    $geoname = Ipalaus\Geonames\Eloquent\Name::where('alternate_names', 'like', '%'.$authority->place.'%')->orderBy('population', 'desc')->first();
                }

                if (!empty($geoname)) {
                    $authority->lat = $geoname->latitude;
                    $authority->lng = $geoname->longitude;
                    $authority->save();
                    $i++;
                }
            }
        }
        return Redirect::back()->withMessage('Pre ' . $i . ' autorít bola nastavená zemepisná šírka a výška.');
    }

    private function uploadImage($authority)
    {
        $error_messages = array();

        $img = Input::get('primary_image');
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        $authority->removeImage();

        $full = true;
        $filename = $authority->getImagePath($full);
        if (file_put_contents($filename, $data)) {
            $authority->has_image = true;
            $authority->save();
        }
    }

    /**
     * Fill the collection with authorities
     *
     * @param
     * @return Response
     */
    public function destroySelected()
    {
        $authorities = Input::get('ids');
        if (!empty($authorities) > 0) {
            foreach ($authorities as $authority_id) {
                $authority = Authority::find($authority_id);

                SpiceHarvesterRecord::where('identifier', '=', $authority_id)->delete();

                $authority->delete();
            }
        }
        return Redirect::back()->withMessage('Bolo zmazaných ' . count($authorities) . ' autorít');
    }

    public function reindex()
    {
        $i = 0;
        Authority::chunk(200, function ($authorities) use (&$i) {
            $authorities->load('items');
            foreach ($authorities as $authority) {
                $authority->index();
                $i++;
                if (App::runningInConsole()) {
                    if ($i % 100 == 0) {
                        echo date('h:i:s'). " " . $i . "\n";
                    }
                }
            }
        });
        $message = 'Bolo reindexovaných ' . $i . ' autorít';
        if (App::runningInConsole()) {
            echo $message;
            return true;
        }
        return Redirect::back()->withMessage($message);

    }
}
