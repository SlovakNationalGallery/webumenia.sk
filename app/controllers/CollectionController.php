<?php

class CollectionController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$collections = Collection::orderBy('created_at', 'desc')->with(['user'])->paginate(20);
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
			$collection->publish = Input::get('publish', false);
			if (Input::has('title_color')) {
				$collection->title_color = Input::get('title_color');
			}
			if (Input::has('title_shadow')) {
				$collection->title_shadow = Input::get('title_shadow');
			}
			$collection->order = Collection::max('order') + 1;
			$collection->user_id = Auth::user()->id;
			$collection->save();

			if (Input::hasFile('main_image')) {
				$this->uploadMainImage($collection);
			}

			return Redirect::route('collection.index');
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
			return Redirect::route('collection.index');
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
			$collection->text = Input::get('text');
			$collection->publish = Input::get('publish', false);
			if (Input::has('title_color')) {
				$collection->title_color = Input::get('title_color');
			}
			if (Input::has('title_shadow')) {
				$collection->title_shadow = Input::get('title_shadow');
			}
			$collection->order = Input::get('order');
			$collection->save();

			if (Input::hasFile('main_image')) {
				$this->uploadMainImage($collection);
			}

			Session::flash('message', 'Kolekcia <code>'.$collection->name.'</code> bola upravená');
			return Redirect::route('collection.index');
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
		return Redirect::route('collection.index')->with('message', 'Kolekcia bola zmazaná');;
	}

	/**
	 * Fill the collection with items
	 *
	 * @param  
	 * @return Response
	 */
	public function fill()
	{

		if ($collection = Collection::find(Input::get('collection'))) {
			$items = Input::get('ids');
			if (!is_array($items)) {
				$items = explode(';', str_replace(" ","",$items));
			}
			foreach ($items as $item_id) {
				if (!$collection->items->contains($item_id)) {
				    $collection->items()->attach($item_id);
				}
			}
			return Redirect::back()->withMessage('Do kolekcie ' . $collection->name . ' bolo pridaných ' . count($items) . ' diel');
		} else {
			return Redirect::back()->withMessage('Chyba: zvolená kolekcia nebola nájdená. ');
		}
	}

	public function detach($collection_id, $item_id) {
		$collection = Collection::find($collection_id);
		$collection->items()->detach($item_id);
		return Redirect::back()->withMessage('Z kolekcie ' . $collection->name . ' bolo odstrádené ' . count($item_id) . ' dielo');
	}

	private function uploadMainImage($collection) {
		$main_image = Input::file('main_image');
		$uploaded_image = Image::make($main_image->getRealPath());
		$uploaded_image->widen(1200);
		$filename = $collection->getHeaderImage(true);
		$uploaded_image->save($filename);
	}

}