<?php

namespace App\Http\Controllers;

use App\Space;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Link;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;


class SpaceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $spaces = Space::orderBy('updated_at', 'DESC')->paginate(100);
        return view('spaces.index', array('spaces' => $spaces));
    }

    /**
     * Find and display a listing
     *
     * @return Response
     */
    public function search()
    {

        $search = Input::get('search');
        $results = Space::where('name', 'LIKE', '%'.$search.'%')->orWhere('id', 'LIKE', '%'.$search.'%')->orderBy('view_count', '')->paginate(20);

        return view('spaces.index', array('spaces' => $results, 'search' => $search));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('spaces.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = Input::all();

        $rules = Space::$rules;
        // $rules['primary_image'] = 'required|image';

        $v = Validator::make($input, $rules);

        if ($v->passes()) {

            $input = convertEmptyStringsToNull($input);

            $space = new Space;

            // not sure if OK to fill all input like this before setting translated attributes
            $space->fill($input);

            // store translatable attributes
            foreach (\Config::get('translatable.locales') as $i=>$locale) {
                foreach ($space->translatedAttributes as $attribute) {
                    $space->translateOrNew($locale)->$attribute = Input::get($locale . '.' . $attribute);
                }

                foreach ($request->input('document.'.$locale, []) as $i=>$file) {
                    $space->addMedia(storage_path('tmp/uploads/' . $file))
                        ->usingName( $request->input('document_name.'.$locale.'.'.$i, '') )
                        ->toCollection('document.'.$locale);
                }
            }

            $space->save();

            if (Input::has('tags')) {
                $space->reTag(Input::get('tags', []));
            }

            if (Input::hasFile('primary_image')) {
                $this->uploadImage($space);
            }

            return Redirect::route('space.index');
        }
        return Redirect::back()->withInput()->withErrors($v);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $space = Space::find($id);

        if (is_null($space)) {
            return Redirect::route('space.index');
        }

        return view('spaces.form')->with('space', $space);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $v = Validator::make(Input::all(), Space::$rules);
        if ($v->passes()) {
            $input = array_except(Input::all(), array('_method'));

            $input = convertEmptyStringsToNull(Input::all());

            $space = Space::find($id);
            $space->fill($input);
            $space->save();
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
                        $space->links()->save($new_link);
                    }
                }
            }

            if (Input::has('tags')) {
                $space->reTag(Input::get('tags', []));
            }

            // ulozit primarny obrazok. do databazy netreba ukladat. nazov=id
            if (Input::has('primary_image')) {
                $this->uploadImage($space);
            }


            foreach (\Config::get('translatable.locales') as $i=>$locale) {

                if (count($space->getMedia('document.'.$locale)) > 0) {
                    foreach ($space->getMedia('document.'.$locale) as $media) {
                        if (!in_array($media->file_name, $request->input('document.'.$locale, []))) {
                            $media->delete();
                        }
                    }


                }

                $media = (count($space->getMedia('document.'.$locale)) > 0) ? $space->getMedia('document.'.$locale)->pluck('file_name')->toArray() : [];
                foreach ($request->input('document.'.$locale, []) as $i=>$file) {
                    if (count($media) === 0 || !in_array($file, $media)) {
                        $space->addMedia(storage_path('tmp/uploads/' . $file))
                            ->usingName( $request->input('document_name.'.$locale.'.'.$i, '') )
                            ->toCollection('document.'.$locale);
                    }
                }
            }


            Session::flash('message', 'Priestor ' .$id. ' bol upravený');
            return Redirect::route('space.index');
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
        $table = 'spaces';
        $newline = "\n";

        $prefix = 'SVK:TMP.';
        $spaces = Space::where('id', 'LIKE', $prefix.'%')->get();
        foreach ($spaces as $key => $space) {
            $space_data = $space->toArray();

            $keys = array();
            $values = array();
            foreach ($space_data as $key => $value) {
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
        $spaces = Space::where('place', '!=', '')->get();
        $i = 0;
        foreach ($spaces as $space) {
            if (!empty($space->place) && (empty($space->lat))) {
                $geoname = Ipalaus\Geonames\Eloquent\Name::where('name', 'like', $space->place)->orderBy('population', 'desc')->first();
                //ak nevratil, skusim podla alternate_names
                if (empty($geoname)) {
                    $geoname = Ipalaus\Geonames\Eloquent\Name::where('alternate_names', 'like', '%'.$space->place.'%')->orderBy('population', 'desc')->first();
                }

                if (!empty($geoname)) {
                    $space->lat = $geoname->latitude;
                    $space->lng = $geoname->longitude;
                    $space->save();
                    $i++;
                }
            }
        }
        return Redirect::back()->withMessage('Pre ' . $i . ' autorít bola nastavená zemepisná šírka a výška.');
    }

    private function uploadImage($space)
    {
        $error_messages = array();

        $img = Input::get('primary_image');
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        $space->removeImage();

        $full = true;
        $filename = $space->getImagePath($full);
        if (file_put_contents($filename, $data)) {
            $space->has_image = true;
            $space->save();
        }
    }

    /**
     * Fill the collection with spaces
     *
     * @param
     * @return Response
     */
    public function destroySelected()
    {
        $spaces = Input::get('ids');
        if (!empty($spaces) > 0) {
            foreach ($spaces as $space_id) {
                $space = Space::find($space_id);
                $space->delete();
            }
        }
        return Redirect::back()->withMessage('Bolo zmazaných ' . count($spaces) . ' autorít');
    }


    public function storeMedia(Request $request)
    {
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

}
