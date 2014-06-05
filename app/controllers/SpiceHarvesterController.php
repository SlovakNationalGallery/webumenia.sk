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

		$harvest::$datum = '2014-03-17'; //docasne, nech ich nieje 100000

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

	    $recs = $myEndpoint->listRecords($harvest->metadata_prefix, $harvest::$datum, NULL, 'Europeana SNG');
	    $dt = new \DateTime;
	    $new_items = 0;
	    $updated_items = 0;
	    while($rec = $recs->nextItem()) {
	        // $this->isDeletedRecord($record) // zisti ci je zmazany
	        // $this->_recordExists($record); // neexistuje este?
	        // $harvestedRecord = $this->_harvestRecord($record);
	        // ak este neexistuje, tak ho vloz $this->_insertItem() inak iba obnov _updateItem()

            $OAI_DC_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';
		    $DUBLIN_CORE_NAMESPACE_ELEMTS = 'http://purl.org/dc/elements/1.1/';
		    $DUBLIN_CORE_NAMESPACE_TERMS = 'http://purl.org/dc/terms/';

			$dcElements = $rec->metadata
		                    ->children($OAI_DC_NAMESPACE)
		                    ->children($DUBLIN_CORE_NAMESPACE_ELEMTS);

			$dcTerms = $rec->metadata
		                    ->children($OAI_DC_NAMESPACE)
		                    ->children($DUBLIN_CORE_NAMESPACE_TERMS);

		    // $dcMetadata = array_merge($dcElements, $dcTerms);

		    $record = new SpiceHarvesterRecord();
		    $record->harvest_id = $id;
		    $record->item_id = $id;
		    $record->identifier = $rec->header->identifier;
		    $record->datestamp = $rec->header->datestamp;
		    $record->created_at = $dt->format('m-d-y H:i:s');
		    $record->updated_at = $dt->format('m-d-y H:i:s');
		    $record->save();

		    $item = new Item();
		    $item->id = $rec->header->identifier;
		    $item->title = $dcElements->title;
		    // dd($dcElements->creator)
		    $item->authorName = implode("; ", (array)$dcElements->creator);
		    $item->dating = $dcTerms->created;
		    $item->workType = $dcElements->type[0];
		    $item->save();
		    $new_items++;


	        $metadata = array();
	        $elementTexts = array();
	        $fileMetadata = array();

	    }

	    $harvest->status00
	    $harvest->save();

	    Session::flash('message', 'Naimportovaných bolo ' . $new_items . ' nvých diel a ' . $new_items . ' bolo upravených.' );
	    return Redirect::route('harvests.index');
	    
	}



}