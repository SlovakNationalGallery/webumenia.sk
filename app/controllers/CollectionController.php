<?php

class CollectionController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$collections = Collection::orderBy('order', 'ASC')->with('items')->paginate(20);
		// $collections = Item::orderBy('created_at', 'DESC')->get();
        return View::make('collections.index')->with('collections', $collections);
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('collections.form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();

		$rules = Collection::$rules;
		$v = Validator::make($input, $rules);

		if ($v->passes()) {
			
			$collection = new Collection;
			$collection->name = Input::get('name');
			$collection->type = Input::get('type');
			$collection->text = Input::get('text');
			$collection->save();

			return Redirect::route('collections.index');
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
		$collection = Collection::find($id);
        return View::make('collections.show')->with('collection', $collection);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$collection = Collection::find($id);

		if(is_null($collection))
		{
			return Redirect::route('collections.index');
		}

        return View::make('collections.form')->with('collection', $collection);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$v = Validator::make(Input::all(), Collection::$rules);

		if($v->passes())
		{
			$input = array_except(Input::all(), array('_method'));

			$collection = Collection::find($id);
			$collection->name = Input::get('name');
			$collection->type = Input::get('type');
			$collection->text = Input::get('text');
			$collection->save();

			Session::flash('message', 'Kolekcia bola upravená');
			return Redirect::route('collections.index');
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
		Collection::find($id)->delete();
		return Redirect::route('collections.index');
	}

}