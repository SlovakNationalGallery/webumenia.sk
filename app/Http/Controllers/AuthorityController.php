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
use Illuminate\Http\Request;


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
    public function store(Request $request)
    {
        $input = Input::all();

        $v = Validator::make($input, Authority::$rules);

        if ($v->passes()) {

            $input = convertEmptyStringsToNull($input);

            $authority = new Authority;

            // not sure if OK to fill all input like this before setting translated attributes
            $authority->fill($input);

            // store translatable attributes
            foreach (\Config::get('translatable.locales') as $i=>$locale) {
                foreach ($authority->translatedAttributes as $attribute) {
                    $authority->translateOrNew($locale)->$attribute = Input::get($locale . '.' . $attribute);
                }

                foreach ($request->input('document.'.$locale, []) as $i=>$file) {
                    $authority->addMedia(storage_path('tmp/uploads/' . $file))
                        ->usingName( $request->input('document_name.'.$locale.'.'.$i, '') )
                        ->toCollection('document.'.$locale);
                }
            }

            $authority->save();

            if (Input::has('tags')) {
                $authority->reTag(Input::get('tags', []));
                $authority->index(); // force reindex - because if nothing else get updated, it wouldn't reindex autmaticaly
            }

            if (Input::hasFile('primary_image')) {
                $this->uploadImage($authority);
            }

            if ($request->hasFile('frontpage_image')) {
                $this->uploadFrontendImage($request, $authority);
            }

            return Redirect::route('authority.index');
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
        $authority = Authority::find($id);

        if (is_null($authority)) {
            return Redirect::route('authority.index');
        }

        $media_files = [];
        foreach (\Config::get('translatable.locales') as $locale) {
            $media_files[$locale] = [];
            foreach ($authority->getMedia('document.'.$locale) as $media) {
                $media_files[$locale][] = [
                    'id' => $media->id,
                    'file_name' => $media->file_name,
                    'name' => $media->name,
                    'size' => $media->size,
                    'path' => $media->getUrl()
                ];
            }
        }

        $artwork_media_files = [];
        foreach ($authority->artworks as $media) {
            $artwork_media_files[] = [
                'id' => $media->id,
                'file_name' => $media->file_name,
                'size' => $media->size,
                'path' => $media->getUrl(),
                // sk
                'title_sk' => $media->getCustomProperty('title.sk'),
                'sub_title_sk' => $media->getCustomProperty('sub_title.sk'),
                'photo_credit_sk' => $media->getCustomProperty('photo_credit.sk'),
                // en
                'title_en' => $media->getCustomProperty('title.en'),
                'sub_title_en' => $media->getCustomProperty('sub_title.en'),
                'photo_credit_en' => $media->getCustomProperty('photo_credit.en'),
            ];
        }

        return view('authorities.form', [
            'authority'=>$authority,
            'media_files'=>$media_files,
            'artwork_media_files'=>$artwork_media_files,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $v = Validator::make(Input::all(), Authority::$rules);
        if ($v->passes()) {
            $input = array_except(Input::all(), array('_method'));

            $input = convertEmptyStringsToNull(Input::all());

            $authority = Authority::find($id);
            $authority->fill($input);
            $authority->touch();
            $authority->save();
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

            if (Input::has('tags')) {
                $authority->reTag(Input::get('tags', []));
                $authority->index();  // force reindex - because if nothing else get updated, it wouldn't reindex autmaticaly
            }

            // ulozit primarny obrazok. do databazy netreba ukladat. nazov=id
            if (Input::has('primary_image')) {
                $this->uploadImage($authority);
            }

            if ($request->hasFile('frontpage_image')) {
                $this->uploadFrontendImage($request, $authority);
            }

            // handle artworks
            $artworks = $authority->artworks;
            foreach ($artworks as $media) {
                if (!in_array($media->file_name, $request->input('artwork', []))) {
                    $media->delete();
                }
            }

            $media = (count($artworks) > 0) ? $artworks->pluck('file_name')->toArray() : [];
            foreach ($request->input('artwork', []) as $i=>$file) {
                if (count($media) === 0 || !in_array($file, $media)) {
                    $authority->addMedia(storage_path('tmp/uploads/' . $file))
                        // ->usingName( $request->input('artwork_name.'.$i, '') )
                        ->withCustomProperties([
                            'title.sk' => $request->input('artwork_title_sk.'.$i, ''),
                            'sub_title.sk' => $request->input('artwork_sub_title_sk.'.$i, ''),
                            'photo_credit.sk' => $request->input('artwork_photo_credit_sk.'.$i, ''),
                            'title.en' => $request->input('artwork_title_en.'.$i, ''),
                            'sub_title.en' => $request->input('artwork_sub_title_en.'.$i, ''),
                            'photo_credit.en' => $request->input('artwork_photo_credit_en.'.$i, ''),
                        ])
                        ->toCollection('artworks');
                } elseif (in_array($file, $media)) {
                    // $artworks[$i]->name = $request->input('artwork_name.'.$i, '');
                    $artworks[$i]->setCustomProperty('title.sk', $request->input('artwork_title_sk.'.$i, ''));
                    $artworks[$i]->setCustomProperty('sub_title.sk', $request->input('artwork_sub_title_sk.'.$i, ''));
                    $artworks[$i]->setCustomProperty('photo_credit.sk', $request->input('artwork_photo_credit_sk.'.$i, ''));
                    $artworks[$i]->setCustomProperty('title.en', $request->input('artwork_title_en.'.$i, ''));
                    $artworks[$i]->setCustomProperty('sub_title.en', $request->input('artwork_sub_title_en.'.$i, ''));
                    $artworks[$i]->setCustomProperty('photo_credit.en', $request->input('artwork_photo_credit_en.'.$i, ''));
                    $artworks[$i]->save();
                }
            }

            foreach (\Config::get('translatable.locales') as $i=>$locale) {

                if (count($authority->getMedia('document.'.$locale)) > 0) {
                    foreach ($authority->getMedia('document.'.$locale) as $media) {
                        if (!in_array($media->file_name, $request->input('document.'.$locale, []))) {
                            $media->delete();
                        }
                    }
                }

                $mediaItems = $authority->getMedia('document.'.$locale);
                $media = (count($mediaItems) > 0) ? $mediaItems->pluck('file_name')->toArray() : [];
                foreach ($request->input('document.'.$locale, []) as $i=>$file) {
                    if (count($media) === 0 || !in_array($file, $media)) {
                        $authority->addMedia(storage_path('tmp/uploads/' . $file))
                            ->usingName( $request->input('document_name.'.$locale.'.'.$i, '') )
                            ->toCollection('document.'.$locale);
                    } elseif (in_array($file, $media)) {
                        if ($mediaItems[$i]->name != $request->input('document_name.'.$locale.'.'.$i, '')) {
                            $mediaItems[$i]->name = $request->input('document_name.'.$locale.'.'.$i, '');
                            $mediaItems[$i]->save();
                        }
                    }
                }
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

    private function uploadFrontendImage(Request $request, $authority)
    {
        $path = public_path($authority::FRONTPAGE_IMG_DIR);

        $file = $request->file('frontpage_image');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        $authority->frontpage_image = $name;
        $authority->save();
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
