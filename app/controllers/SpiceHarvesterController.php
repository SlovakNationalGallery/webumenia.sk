<?php

class SpiceHarvesterController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$harvests = SpiceHarvesterHarvest::orderBy('created_at', 'DESC')->paginate(3);
        return View::make('harvests.index')->with('harvests', $harvests);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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

	/**
	 * Launch the harvest process
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function launch($id)
	{
		$harvest = SpiceHarvesterHarvest::find($id);
		$harvest->start_from = date("Y-m-d H:i:s");

		if ($harvest->status == SpiceHarvesterHarvest::STATUS_COMPLETED) {
            $harvest->start_from = $harvest->initiated;
        } else {
            $harvest->start_from = null;
        }
		
		$harvest->initiated = date('Y:m:d H:i:s');
		$harvest->save();

		dd($harvest::STATUS_COMPLETED);

	}

}