<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use Barryvdh\Debugbar\Facade;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use App\Item;
use App\Import;
use App\ImportRecord;
// use App\Collection;

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
        $import = Import::find($id);
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


        // \Excel::filter('chunk')->load($file)->chunk(250, function($results)
        // {
        //     foreach($results as $row)
        //     {
        //         dd($row);
        //     }
        // });

        try {
            $file = (is_array($file)) ? storage_path('app/' . $file['path']) : $file;
            \Excel::load($file, function ($reader) use (&$this_import_record) {
                foreach ($reader->toArray() as $row) {
                    // dd($row)
                    $gallery = 'Moravská galerie v Brně, MG';
                    $prefix = 'CZK:MG.';
                    $id = $prefix . $row['rada_s'] . '_' . (int)$row['porc_s'];
                    $identifier = $row['rada_s'] . ' ' . (int)$row['porc_s'];

                    $suffix = ($row['lomeni_s'] != '_') ? $row['lomeni_s'] : '';
                    if ($suffix) {
                        $id .= '-' . $suffix;
                        $identifier .= '/' .$suffix;
                    }

                    
                    $item = Item::firstOrNew(['id' => $id]);
                    $item->identifier = $identifier;
                    $item->gallery = $gallery;
                    $item->acquisition_date = $row['rokakv'];
                    $item->author = ($row['autor']) ? $row['autor'] : 'Neznámy autor';
                    $item->copyright_expires = $row['datexp'];
                    $item->dating = $row['datace'];
                    $item->date_earliest = $row['rokod'];
                    $item->date_latest = $row['do'];
                    $item->place = $row['mistovz'];
                    $item->title = $row['titul'];
                    $medium = ($row['matspec']) ? ($row['material'] . ', ' . $row['matspec']) : $row['material'];
                    $item->medium = $medium;
                    $technique = ($row['techspec']) ? ($row['technika'] . ', ' . $row['techspec']) : $row['technika'];
                    $item->technique = $technique;
                    $item->topic = ($row['namet']) ? $row['namet'] : '';
                    $item->inscription = $row['sign'];
                    $work_type = Import::getWorkType($row['rada_s'], $row['skupina']);
                    $item->work_type = $work_type;
                    // este budu miry
                    $item->save();
                    $this_import_record->imported_items++;
                    $this_import_record->save();
                };
                $this_import_record->status = Import::STATUS_COMPLETED;
                $this_import_record->completed_at = date('Y-m-d H:i:s');
                $this_import_record->save();

            },  'Windows-1250');
            \Session::flash('message', $this_import_record->imported_items . ' records imported successfully.');
            return redirect(route('imports.index'));
        } catch (\Exception $e) {
            $this_import_record->status = Import::STATUS_ERROR;
            $this_import_record->completed_at = date('Y-m-d H:i:s');
            $this_import_record->save();
            \Session::flash('error', $e->getMessage());
            // return redirect(route('imports.index'));
        }

        // $this_import_record->completed_at = date('Y-m-d H:i:s');
        // $this_import_record->save();
        // return redirect(route('imports.index'));


	}

}
