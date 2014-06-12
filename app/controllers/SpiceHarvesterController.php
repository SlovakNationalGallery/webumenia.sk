<?php

class SpiceHarvesterController extends \BaseController {

    const OAI_DATE_FORMAT = 'Y-m-d';

    const METADATA_SCHEMA = 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd';
    const METADATA_PREFIX = 'oai_dc';

    const OAI_DC_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';
    const DUBLIN_CORE_NAMESPACE_ELEMTS = 'http://purl.org/dc/elements/1.1/';
    const DUBLIN_CORE_NAMESPACE_TERMS = 'http://purl.org/dc/terms/';

    private $start_from;

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
		$processed_items = 0;
	    $new_items = 0;
	    $updated_items = 0;
	    $timeStart = microtime(true);

	    $first_record_id = '';

		$harvest = SpiceHarvesterHarvest::find($id);

		if ($harvest->status == SpiceHarvesterHarvest::STATUS_COMPLETED) {
            $this->start_from = $harvest->initiated;
        } else {
            $this->start_from = null;
        }
		// $this->start_from = '2014-03-17'; //docasne, nech ich nieje 100000
		
		$harvest->status = $harvest::STATUS_QUEUED;
		$harvest->initiated = date('Y:m:d H:i:s');
		$harvest->save();


		$harvest->status = $harvest::STATUS_IN_PROGRESS;
		$harvest->save();


		//--- nazacat samostatnu metodu?
		$client = new \Phpoaipmh\Client($harvest->base_url);
	    $myEndpoint = new \Phpoaipmh\Endpoint($client);

	    $recs = $myEndpoint->listRecords($harvest->metadata_prefix, $this->start_from, NULL, $harvest->set_spec);
	    $dt = new \DateTime;

	    while($rec = $recs->nextItem()) {
	    	/*
	    	if (empty($first_record_id)) {
	    		$first_record_id = $rec->header->identifier;
	    	} else {
	    		if ($first_record_id == $rec->header->identifier) {
					var_dump($rec);
					$totalTime = round((microtime(true)-$timeStart));
	    			dd('narazil po ' . $processed_items . ' zaznamoch a trvalo to ' . $totalTime);
	    		}
	    	}
			*/

	    	$processed_items++;
	    	/*
	    	var_dump("#$processed_items : {$rec->header->identifier} <br />\n");
	    	if ($processed_items > 200) {die();}
	    	*/

	    	if (!$this->isDeletedRecord($rec)) {


				$existingRecord = SpiceHarvesterRecord::where('identifier', '=', $rec->header->identifier)->first();

		        if ($existingRecord) {
		            // ak sa zmenil datestamp, update item - inak ignorovat
		            if($existingRecord->datestamp != $rec->header->datestamp) {
		                $this->updateItem($existingRecord, $rec);
		                $updated_items++;
		            }
		        } else {
		            $this->insertItem($id, $rec);
		            $new_items++;
		        }

   	    	}


	    }

	    $harvest->status = SpiceHarvesterHarvest::STATUS_COMPLETED;
	    $harvest->save();

	    $totalTime = round((microtime(true)-$timeStart));

	    Session::flash('message', 'Spracovaných bolo ' . $processed_items . 'diel. Z toho pribudlo ' . $new_items . ' nvých diel a ' . $new_items . ' bolo upravených. Celé to trvalo ' . $totalTime . 's' );
	    return Redirect::route('harvests.index');
	    
	}


    /**
     * Return whether the record is deleted
     * 
     * @param SimpleXMLIterator The record object
     * @return bool
     */
    private function isDeletedRecord($rec)
    {
        if (isset($rec->header->attributes()->status) 
            && $rec->header->attributes()->status == 'deleted') {
            return true;
        }
        return false;
    }

    /**
     * Convenience method for inserting an item and its files.
     * 
     * Method used by map writers that encapsulates item and file insertion. 
     * Items are inserted first, then files are inserted individually. This is 
     * done so Item and File objects can be released from memory, avoiding 
     * memory allocation issues.
     * 
     * @param int $harvest_id
     * @param SimpleXMLElement $rec OAI PMH record
     * @return true
     */
    private function insertItem($harvest_id, $rec) {

    	// Insert the item
    	$itemAttributes = $this->mapAttributes($rec);
	    $item = Item::create($itemAttributes);


		// Insert the record after the item is saved
	    $record = new SpiceHarvesterRecord();
	    $record->harvest_id = $harvest_id;
	    $record->item_id = $itemAttributes['id'];
	    $record->identifier = $rec->header->identifier;
	    $record->datestamp = $rec->header->datestamp;
	    $record->save();

    	/*
      
        // If there are files, insert one file at a time so the file objects can 
        // be released individually.
        if (isset($fileMetadata['files'])) {
            
            // The default file transfer type is URL.
            $fileTransferType = isset($fileMetadata['file_transfer_type']) 
                              ? $fileMetadata['file_transfer_type'] 
                              : 'Url';
            
            // The default option is ignore invalid files.
            $fileOptions = isset($fileMetadata['file_ingest_options']) 
                         ? $fileMetadata['file_ingest_options'] 
                         : array('ignore_invalid_files' => true);
            
            // Prepare the files value for one-file-at-a-time iteration.
            $files = array($fileMetadata['files']);
            
            foreach ($files as $file) {
                $fileOb = insert_files_for_item(
                    $item, 
                    $fileTransferType, 
                    $file, 
                    $fileOptions);   
                   _log($fileOb);
                   $fileObject= $fileOb;//$fileOb[0];
                   if(!empty($file['metadata'])){
                       $fileObject->addElementTextsByArray($file['metadata']);
                   $fileObject->save();
                   }
                  
                // Release the File object from memory. 
                release_object($fileObject);
            }
        }
        
        // Release the Item object from memory.
        release_object($item);
        */
        return true;
    }
    
    /**
     * Method for updating an item
     * 
     * @param SpiceHarvesterRecord $existingRecord 
     * @param SimpleXML $rec OAI PMH record
     * @return true
     */
    private function updateItem($existingRecord, $rec) {

    	// Update the item
    	$itemAttributes = $this->mapAttributes($rec);
    	$item = Item::where('id', '=', $rec->header->identifier)->first();
    	$item->fill($itemAttributes);
    	$item->save();

        
        // Update the datestamp stored in the database for this record.
	    $existingRecord->datestamp = $rec->header->datestamp;
	    // $existingRecord->updated_at =  date('Y-m-d H:i:s'); //toto by sa malo diat automaticky
	    $existingRecord->save();

        return true;
    }

    /**
     * Map attributes from OAI to internal schema
     */
    private function mapAttributes($rec)
    {
    	$attributes = array();

		$dcElements = $rec->metadata
	                    ->children(self::OAI_DC_NAMESPACE)
	                    ->children(self::DUBLIN_CORE_NAMESPACE_ELEMTS);

		$dcTerms = $rec->metadata
	                    ->children(self::OAI_DC_NAMESPACE)
	                    ->children(self::DUBLIN_CORE_NAMESPACE_TERMS);

	    $type = (array)$dcElements->type;
	    $identifier = (array)$dcElements->identifier;

	    $attributes['id'] = $rec->header->identifier;
	    $attributes['title'] = $dcElements->title;
	    $attributes['author'] = implode("; ", (array)$dcElements->creator);
	    $attributes['work_type'] = $type[0];
	    $attributes['work_level'] = $type[1];
	    $attributes['topic'] = $dcElements->subject;
	    $attributes['measurement'] = $dcTerms->extent;
	    $attributes['dating'] = $dcTerms->created;
	    $dating = explode('-', $dcTerms->created);
	    $attributes['date_earliest'] = (!empty($dating[0])) ? $dating[0] : null;
	    $attributes['date_latest'] = (!empty($dating[1])) ? $dating[1] : null;
	    $attributes['medium'] = $dcElements->{'format.medium'}; // http://stackoverflow.com/questions/6531380/php-simplexml-with-dot-character-in-element-in-xml
	    $attributes['technique'] = $dcElements->format;
	    $attributes['inscription'] = implode("; ", (array)$dcElements->description);
	    $attributes['state_edition'] =  (!empty($type[2])) ? $type[2] : null;
	    $attributes['gallery'] = $dcTerms->provenance;
	    $attributes['img_url'] = (!empty($identifier[1]) && (strpos($identifier[1], 'http') === 0)) ? $identifier[1] : null; //ak nieje prazdne a zacina 'http'

	    return $attributes;
    }
}