<?php

class ItemController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$items = Item::orderBy('created_at', 'DESC')->paginate(20);
		// $collections = Collection::orderBy('order', 'ASC')->get();
		$collections = Collection::lists('name', 'id');
        return View::make('items.index', array('items' => $items, 'collections' => $collections));		
	}

	/**
	 * Find and display a listing
	 *
	 * @return Response
	 */
	public function search()
	{

		$input = Input::get('search');
		$results = Item::where('title', 'LIKE', '%'.$input.'%')->orWhere('author', 'LIKE', '%'.$input.'%')->orWhere('id', 'LIKE', '%'.$input.'%')->paginate(20);

		$collections = Collection::lists('name', 'id');
        return View::make('items.index', array('items' => $results, 'collections' => $collections));		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('items.form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
        return View::make('items.show')->with('item', $item);
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

		if(is_null($item))
		{
			return Redirect::route('item.index');
		}

        return View::make('items.form')->with('item', $item);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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

}