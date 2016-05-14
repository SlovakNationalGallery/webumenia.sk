<?php

namespace App\Http\Controllers;

use App\Sketchbook;
use App\Role;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Item;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class SketchbookController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $sketchbooks = Sketchbook::orderBy('order', 'asc')->get();
        return view('sketchbooks.index')->with('sketchbooks', $sketchbooks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'asc')->lists('name', 'id');
        return view('sketchbooks.form')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();

        $rules = Sketchbook::$rules;
        $v = Validator::make($input, $rules);

        if ($v->passes()) {
            
            $item = Item::find(Input::get('item_id'));

            $sketchbook = new Sketchbook;
            $sketchbook->item_id = $item->id;
            $sketchbook->title = implode(', ', $item->authors)  . ' / ' .  $item->date_latest;
            $sketchbook->order = Sketchbook::max('order') + 1;
            $sketchbook->width = $item->width;
            $sketchbook->height = $item->height;
            $sketchbook->save();

            Session::flash('message', 'Skicár ' .$sketchbook->name. ' bol vytvorený');

            return Redirect::route('sketchbook.index');
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
        $sketchbook = Sketchbook::find($id);
        return view('sketchbooks.show')->with('sketchbook', $sketchbook);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $sketchbook = Sketchbook::find($id);

        if (is_null($sketchbook)) {
            return Redirect::route('sketchbook.index');
        }

        $roles = Role::orderBy('name', 'asc')->lists('name', 'id');
        return view('sketchbooks.form')->with('sketchbook', $sketchbook)->with('roles', $roles);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $v = Validator::make(Input::all(), Sketchbook::$rules);
        // dd(Input::all());
        if ($v->passes()) {
            $input = array_except(Input::all(), array('_method'));

            $sketchbook = Sketchbook::find($id);
            $sketchbook->fill($input);
            $sketchbook->save();

            Session::flash('message', 'Skicár ' .$sketchbook->name. ' bol upravený');
            return Redirect::route('sketchbook.index');
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
        Sketchbook::find($id)->delete();
        return Redirect::route('sketchbook.index')->with('message', 'Užívateľ bol zmazaný');
        ;
    }
}
