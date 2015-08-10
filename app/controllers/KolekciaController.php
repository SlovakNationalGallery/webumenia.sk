<?php

class KolekciaController extends \BaseController {

	public function getIndex()
	{
		$per_page = 18;
		if (Input::has('sort_by') && array_key_exists(Input::get('sort_by'), Collection::$sortable)) {
			$sort_by = Input::get('sort_by');
		} else {
			$sort_by = 'created_at';
		}
		$sort_order = ($sort_by == 'name') ? 'asc' : 'desc';

		$collections = Collection::published()->with('user')->orderBy($sort_by, $sort_order)->paginate($per_page);
		return View::make('kolekcie', array('collections'=>$collections, 'sort_by'=>$sort_by));
	}

	public function getDetail($id)
	{
		// dd($id);
		$collection = Collection::find($id);
		if (empty($collection)) {
			App::abort(404);
		}
		$collection->view_count += 1; 
		$collection->save();

		return View::make('kolekcia', array('collection'=>$collection));

	}


}