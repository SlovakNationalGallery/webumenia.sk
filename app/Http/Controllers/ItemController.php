<?php

class ItemController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = Item::orderBy('updated_at', 'DESC')->paginate(100);
        // $collections = Collection::orderBy('order', 'ASC')->get();
        $collections = Collection::lists('name', 'id');
        return view('items.index', array('items' => $items, 'collections' => $collections));
    }

    /**
     * Find and display a listing
     *
     * @return Response
     */
    public function search()
    {

        $search = Input::get('search');
        if (str_contains($search, ';')) {

            $ids = explode(';', str_replace(" ", "", $search));
            $results = Item::whereIn('id', $ids)->paginate(20);
        } else {
            $results = Item::where('title', 'LIKE', '%'.$search.'%')->orWhere('author', 'LIKE', '%'.$search.'%')->orWhere('id', 'LIKE', '%'.$search.'%')->paginate(20);
        }

        $collections = Collection::lists('name', 'id');
        return view('items.index', array('items' => $results, 'collections' => $collections, 'search' => $search));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $prefix = 'SVK:TMP.';  // TMP = temporary
        $pocet = Item::where('id', 'LIKE', $prefix.'%')->count();
        $new_id = $prefix . ($pocet+1);
        return view('items.form', array('new_id'=>$new_id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();

        $rules = Item::$rules;
        $rules['primary_image'] = 'required|image';

        $v = Validator::make($input, $rules);

        if ($v->passes()) {

            $input = array_filter($input, 'strlen');
            $item = new Item;
            $item->fill($input);
            $item->save();

            if (Input::hasFile('primary_image')) {
                $this->uploadImage($item);
            }

            return Redirect::route('item.index');
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
        $item = Item::find($id);
        return view('items.show')->with('item', $item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $item = Item::find($id);

        if (is_null($item)) {
            return Redirect::route('item.index');
        }

        return view('items.form')->with('item', $item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $v = Validator::make(Input::all(), Item::$rules);

        if ($v->passes()) {
            $input = array_except(Input::all(), array('_method'));
            // $input = array_filter($input, 'strlen');
            $input = array_map(function ($e) {
                return $e ?: null;

            }, Input::all()); //prazdne hodnoty zmeni na null

            $item = Item::find($id);
            $item->fill($input);
            $item->save();

            if (Input::has('tags')) {
                $item->reTag(Input::get('tags', []));
                $item->index(); //pre istotu. lebo ak sa nic ine nezmeni, tak nepreindexuje
            }

            // ulozit primarny obrazok. do databazy netreba ukladat. nazov=id
            if (Input::hasFile('primary_image')) {
                $this->uploadImage($item);
            }

            Session::flash('message', 'Dielo ' .$id. ' bolo upravené');
            return Redirect::route('item.index');
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
        Item::find($id)->delete();
        return Redirect::route('item.index')->with('message', 'Dielo bolo zmazané');
        ;
    }

    public function backup()
    {
        $sqlstring = "";
        $table = 'items';
        $newline = "\n";

        $prefix = 'SVK:TMP.';
        $items = Item::where('id', 'LIKE', $prefix.'%')->get();
        foreach ($items as $key => $item) {
            $item_data = $item->toArray();
            
            $keys = array();
            $values = array();
            foreach ($item_data as $key => $value) {
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
        $items = Item::where('place', '!=', '')->get();
        $i = 0;
        foreach ($items as $item) {
            if (!empty($item->place) && (empty($item->lat))) {
                $geoname = Ipalaus\Geonames\Eloquent\Name::where('name', 'like', $item->place)->orderBy('population', 'desc')->first();
                //ak nevratil, skusim podla alternate_names
                if (empty($geoname)) {
                    $geoname = Ipalaus\Geonames\Eloquent\Name::where('alternate_names', 'like', '%'.$item->place.'%')->orderBy('population', 'desc')->first();
                }

                if (!empty($geoname)) {
                    $item->lat = $geoname->latitude;
                    $item->lng = $geoname->longitude;
                    $item->save();
                    $i++;
                }
            }
        }
        return Redirect::back()->withMessage('Pre ' . $i . ' diel bola nastavená zemepisná šírka a výška.');
    }

    private function uploadImage($item)
    {
        $item->removeImage();
        
        $error_messages = array();
        $primary_image = Input::file('primary_image');
        $full = true;
        $filename = $item->getImagePath($full);
        $uploaded_image = Image::make($primary_image->getRealPath());
        if ($uploaded_image->width() > $uploaded_image->height()) {
            $uploaded_image->widen(800);
        } else {
            $uploaded_image->heighten(800);
        }
        $uploaded_image->save($filename);
    }

    /**
     * Destroy selected items
     *
     * @param
     * @return Response
     */
    public function destroySelected()
    {
        $items = Input::get('ids');
        if (!empty($items) > 0) {
            foreach ($items as $item_id) {
                $item = Item::find($item_id);
                $image = $item->getImagePath(true); // fullpath, disable no image
                if ($image) {
                    @unlink($image);
                }
                $item->collections()->detach();

                SpiceHarvesterRecord::where('identifier', '=', $item_id)->delete();
                
                $item->delete();
            }
        }
        return Redirect::back()->withMessage('Bolo zmazaných ' . count($items) . ' diel');
    }

    /**
     * Refresh selected items with data from OAI-PMH source
     *
     * @param
     * @return Response
     */
    public function refreshSelected()
    {
        $items = Input::get('ids');
        if (!empty($items) > 0) {
            foreach ($items as $item_id) {
                $item = Item::find($item_id);
                App::make('SpiceHarvesterController')->refreshSingleRecord($item->record->id);
            }
        }
        return Redirect::back()->withMessage('Pre ' . count($items) . ' diel boli načítané dáta z OAI');
    }

    public function reindex()
    {
        $i = 0;

        Item::chunk(200, function ($items) use (&$i) {
            $items->load('authorities');
            foreach ($items as $item) {
                $item->index();
                $i++;
                if (App::runningInConsole()) {
                    if ($i % 100 == 0) {
                        echo date('h:i:s'). " " . $i . "\n";
                    }
                }
            }
        });
        $message = 'Bolo reindexovaných ' . $i . ' diel';
        if (App::runningInConsole()) {
            echo $message;
            return true;
        }
        return Redirect::back()->withMessage($message);
    }
}
