<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Import;
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
            $import = new Import;
            $import->name = Input::get('name');
            // $import->type = Input::get('type');
            // $collection = Collection::find(Input::get('collection_id'));
            // if ($collection) {
            //     $import->collection()->associate($collection);
            // }
            $import->save();

            Session::flash('message', 'Import <code>'.$import->set_spec.'</code> bol upravený');
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
}
