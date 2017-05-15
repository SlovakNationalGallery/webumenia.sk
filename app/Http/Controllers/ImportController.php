<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use Barryvdh\Debugbar\Facade;
use Carbon\Carbon;

use App\Item;
use App\Import;
use App\ImportRecord;
use League\Csv\Reader;

class ImportController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $imports = Import::orderBy('created_at', 'DESC')->paginate(10);
        return view('imports.index')->with('imports', $imports);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('imports.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();

        $rules = Import::$rules;
        $v = Validator::make($input, $rules);

        if ($v->passes()) {
            
            $import = new Import;
            $import->name = Input::get('name');
            $import->dir_path = Input::get('dir_path');
            // $import->type = Input::get('type');
            // $collection = Collection::find(Input::get('collection_id'));
            // if ($collection) {
            //     $import->collection()->associate($collection);
            // }
            $import->save();

            return redirect()->route('imports.index');
        }

        return back()->withInput()->withErrors($v);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $import = Import::with(['records' => function($query) {
            $query->orderBy('id', 'desc');
            $query->take(50);
        }])->find($id);
        return view('imports.show')->with('import', $import);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $import = Import::find($id);

        if (is_null($import)) {
            return redirect()->route('import.index');
        }

        return view('imports.form')->with('import', $import);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $v = Validator::make(Input::all(), Import::$rules);

        if ($v->passes()) {
            $input = array_except(Input::all(), array('_method'));

            $import = Import::find($id);
            $import->name = Input::get('name');
            $import->dir_path = Input::get('dir_path');
            // $import->type = Input::get('type');
            // $collection = Collection::find(Input::get('collection_id'));
            // if ($collection) {
            //     $import->collection()->associate($collection);
            // }
            $import->save();

            if (Input::hasFile('file')) {
                $file = Input::file('file');
                return $this->launch($import, $file);
            }


            \Session::flash('message', 'Import <code>'.$import->name.'</code> bol upravený');
            return redirect()->route('imports.index');
        }

        return back()->withErrors($v);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $import = Import::find($id);
        $name = $import->name;
        $import->delete();
        return redirect()->route('imports.index')->with('message', 'Import <code>'.$name.'</code>  bol zmazaný');
        ;
    }

	/**
	 * Launch the import
	 *
	 * @param  /App/Import $import
	 * @param  CSV file $file
	 * @return Response
	 */
	public function launch($import, $file)
	{

		\Debugbar::disable();
		$processed_items = 0;
	    $new_items = 0;
	    $updated_items = 0;
	    $skipped_items = 0;
	    $timeStart = microtime(true);

		$last_import_record = $import->lastRecord();

        $start_from = null;
        $reindex = false; // docasne. chcelo by to checkbox

		if ($last_import_record && ($last_import_record->status == Import::STATUS_COMPLETED || $last_import_record->status == Import::STATUS_IN_PROGRESS) && !$reindex) {
            $start_from = $last_import_record->completed_at;
            // $start_from->sub(new DateInterval('P1D'));
        }

        $this_import_record = new ImportRecord;
        $this_import_record->import_id = $import->id;

		$this_import_record->status = $import::STATUS_IN_PROGRESS;
        $this_import_record->started_at = date('Y-m-d H:i:s');
		$this_import_record->filename = (is_array($file)) ? $file['basename'] : $file->getClientOriginalName();
		$this_import_record->save();


        $images = null;
        if ($import->dir_path) {
            $image_dir = basename($this_import_record->filename, '.csv');
            $images = \Storage::listContents('import/' . $import->dir_path . '/' . $image_dir);
        }


        $file = (is_array($file)) ? storage_path('app/' . $file['path']) : $file;

        $convert_encoding_func = function ($row) {
            return array_map('w1250_to_utf8', $row);
        };

        $csv = Reader::createFromPath($file);

        $csv->setDelimiter(';');
        $csv->setEnclosure('"');
        $csv->setEscape("\\");
        $csv->setNewline("\r\n");

        $headers = $csv->fetchOne(0);
        $headers = array_map('w1250_to_utf8', $headers);
        $headers = array_map('remove_accents', $headers);

        $records = $csv->setOffset(1)->fetchAssoc($headers, $convert_encoding_func);

        try {

            foreach ($records as $index => $row) {

                $row = array_map('empty_to_null', $row);

                $gallery = 'Moravská galerie, MG';
                $prefix = 'CZE:MG.';
                $id = $prefix . $row['Rada_S'] . '_' . (int)$row['PorC_S'];
                $identifier = $row['Rada_S'] . ' ' . (int)$row['PorC_S'];
                $image_file = $row['Rada_S'] . str_pad($row['PorC_S'], 6, "0", STR_PAD_LEFT);

                $suffix = ($row['Lomeni_S'] != '_') ? $row['Lomeni_S'] : '';
                if ($suffix) {
                    $id .= '-' . $suffix;
                    $image_file .= '-' . $suffix;
                    $identifier .= '/' .$suffix;
                }

                
                $item = Item::firstOrNew(['id' => $id]);
                $item->identifier = $identifier;
                $item->gallery = $gallery;
                $item->acquisition_date = $row['RokAkv'];
                $item->author = ($row['Autor']) ? $row['Autor'] : 'Neznámy autor';
                $item->copyright_expires = $row['DatExp'];
                $item->dating = $row['Datace'];
                $item->date_earliest = $row['RokOd'];
                $item->date_latest = $row['Do'];
                $item->place = $row['MistoVz'];
                $item->title = $row['Titul'];
                $medium = ($row['MatSpec']) ? ($row['Material'] . ', ' . $row['MatSpec']) : $row['Material'];
                $item->medium = $medium;
                $technique = ($row['TechSpec']) ? ($row['Technika'] . ', ' . $row['TechSpec']) : $row['Technika'];
                $item->technique = $technique;
                $item->topic = ($row['Namet']) ? $row['Namet'] : '';
                $item->inscription = $row['Sign'];
                $work_type = Import::getWorkType($row['Rada_S'], $row['Skupina']);
                $item->work_type = $work_type;
                // add dimiensions, etc...

                if ($images) {
                    $item_image_files = array_filter($images, function ($object) use ($image_file) { 
                        return (
                            $object['type'] === 'file' && 
                            ($object['extension'] === 'jpg' || $object['extension'] === 'JPG' || 
                                $object['extension'] === 'jpeg' || $object['extension'] === 'JPEG') &&
                            strpos($object['filename'], $image_file) === 0
                            ); 
                    });
                    if (!empty($item_image_files)) {
                        $item_image_file = reset($item_image_files);
                        $this->uploadImage($item, $item_image_file);
                        $item->has_image = true;
                        $this_import_record->imported_images++;


                        // detect DeepZoom at IIP - if it's hq (high quality) image
                        if (strpos($item_image_file['filename'], 'hq') !== false) {
                            $iip_img = '/MG/DrevoHQ/jp2/'.$item_image_file['filename'].'.jp2'; // @TODO: change this!
                            $iip_url = 'http://www.webumenia.sk/fcgi-bin/iipsrv.fcgi?DeepZoom='. $iip_img;
                            if (isValidURL($iip_url)) {
                                $item->iipimg_url = $iip_img;
                            }
                        }
                    }
                }

                $item->save();

                $this_import_record->imported_items++;
                $this_import_record->save();
            }

            $this_import_record->status = Import::STATUS_COMPLETED;
            $this_import_record->completed_at = date('Y-m-d H:i:s');
            $this_import_record->save();

            \Session::flash('message', $this_import_record->imported_items . ' records imported successfully.');
            return redirect(route('imports.index'));
        } catch (\Exception $e) {
            $this_import_record->status = Import::STATUS_ERROR;
            $this_import_record->error_message = $e->getMessage();
            $this_import_record->completed_at = date('Y-m-d H:i:s');
            $this_import_record->save();
            \Session::flash('error', $e->getMessage());
            // return redirect(route('imports.index'));
        }

        $this_import_record->completed_at = date('Y-m-d H:i:s');
        $this_import_record->save();
        

        if (\App::runningInConsole()) {
            echo \Session::get('message') . "\n";
            return true;
        }
        
        return redirect(route('imports.index'));

    }

    private function uploadImage($item, $file)
    {
        $item->removeImage();
        
        $error_messages = array();
        $full = true;
        $filename = $item->getImagePath($full);
        $uploaded_image = \Image::make(storage_path('app/' . $file['path']));
        if ($uploaded_image->width() > $uploaded_image->height()) {
            $uploaded_image->widen(800);
        } else {
            $uploaded_image->heighten(800);
        }
        return $uploaded_image->save($filename);
    }

}
