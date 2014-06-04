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
		$harvest::$datum = date("Y-m-d H:i:s");

		if ($harvest->status == SpiceHarvesterHarvest::STATUS_COMPLETED) {
            $harvest::$datum = $harvest->initiated;
        } else {
            $harvest::$datum = null;
        }
		
		$harvest->status = $harvest::STATUS_QUEUED;
		$harvest->initiated = date('Y:m:d H:i:s');
		$harvest->save();

		// inicializacia

        
        $collectionMetadata = array(
            'metadata' => array(
                'public' => 'public',
                'featured' => 'featured',
            ),);
        $collectionMetadata['elementTexts']['Dublin Core']['Title'][]
            = array('text' => (string) $harvest->set_name, 'html' => false); 
        $collectionMetadata['elementTexts']['Dublin Core']['Description'][]
            = array('text' => (string) $harvest->set_Description, 'html' => false); 
        
        // $this->_collection = $this->_insertCollection($collectionMetadata);


		$harvest->status = $harvest::STATUS_IN_PROGRESS;

//		$resumptionToken = $this->_harvestRecords();
		//-------toto bude samostatna metoda _harvestRecords
		$client = new \Phpoaipmh\Client($harvest->base_url);
	    $myEndpoint = new \Phpoaipmh\Endpoint($client);

	    $rec = $myEndpoint->getRecord('SVK:SNG.G_3671', $harvest->metadata_prefix);
	    $myRec = $rec->GetRecord;
	    dd($myRec->record->metadata->children($harvest->metadata_prefix, 1)->dc->children('dc', 1));





		dd($harvest::STATUS_COMPLETED);

	}


}