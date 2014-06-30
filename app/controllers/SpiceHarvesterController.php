<?php

class SpiceHarvesterController extends \BaseController {

    const OAI_DATE_FORMAT = 'Y-m-d';

    const METADATA_SCHEMA = 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd';
    const METADATA_PREFIX = 'oai_dc';

    const OAI_DC_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';
    const DUBLIN_CORE_NAMESPACE_ELEMTS = 'http://purl.org/dc/elements/1.1/';
    const DUBLIN_CORE_NAMESPACE_TERMS = 'http://purl.org/dc/terms/';


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

        $start_from = null;

		if ($harvest->status == SpiceHarvesterHarvest::STATUS_COMPLETED) {
            $start_from = $harvest->initiated;
        } 

		$harvest->status = $harvest::STATUS_QUEUED;
		$harvest->initiated = date('Y:m:d H:i:s');
		$harvest->save();


		$harvest->status = $harvest::STATUS_IN_PROGRESS;
		$harvest->save();


		//--- nazacat samostatnu metodu?
		$client = new \Phpoaipmh\Client($harvest->base_url);
	    $myEndpoint = new \Phpoaipmh\Endpoint($client);

	    $recs = $myEndpoint->listRecords($harvest->metadata_prefix, $start_from, NULL, $harvest->set_spec);
	    $dt = new \DateTime;

	    while($rec = $recs->nextItem()) {
	    	$processed_items++;
	    	
	    	if (!$this->isDeletedRecord($rec)) {

				$existingRecord = SpiceHarvesterRecord::where('identifier', '=', $rec->header->identifier)->first();

		        if ($existingRecord) {
		            // ak sa zmenil datestamp, update item - inak ignorovat
		            // if( $existingRecord->datestamp != $rec->header->datestamp) {
		                $this->updateItem($existingRecord, $rec);
		                $updated_items++;
		            // }
		        } else {
		            $this->insertItem($id, $rec);
		            $new_items++;
		        }

   	    	}
	    }

	    $harvest->status = SpiceHarvesterHarvest::STATUS_COMPLETED;
	    $harvest->save();

	    $totalTime = round((microtime(true)-$timeStart));

	    Session::flash('message', 'Spracovaných bolo ' . $processed_items . ' diel. Z toho pribudlo ' . $new_items . ' nvých diel a ' . $updated_items . ' bolo upravených. Trvalo to ' . $totalTime . 's' );
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

      
        // Upload image given by url

        if (!empty($itemAttributes['img_url'])) {

        	$file = $itemAttributes['img_url'];
        	$data = file_get_contents($file);
        	$full = true;
         	if ($new_file = $item->getImagePath($full)) {
	            file_put_contents($new_file, $data);
         	}
        }
        

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

	    $topic=array(); // zaner - krajina s figuralnou kompoziciou / veduta
	    $subject=array(); // objekt - dome/les/

	    foreach ($dcElements->subject as $key => $value) {
	    	if ($this->starts_with_upper($value)) {
	    		$subject[] = mb_strtolower($value, "UTF-8");
	    	} else {
	    		$topic[] =$value;
	    	}
	    }

	    $attributes['id'] = $rec->header->identifier;
	    $attributes['title'] = $dcElements->title;
	    $attributes['author'] = $this->serialize($dcElements->creator);
	    $attributes['work_type'] = $type[0];
	    $attributes['work_level'] = $type[1];
	    $attributes['topic'] = $this->serialize($topic);
	    $attributes['subject'] = $this->serialize($subject);
	    $attributes['place'] = $this->serialize($dcElements->{'subject.place'});
	    $attributes['measurement'] = $dcTerms->extent;
	    
	    $dating = explode('/', $dcTerms->created);
	    $attributes['date_earliest'] = (!empty($dating[0])) ? $dating[0] : null;
	    $attributes['date_latest'] = (!empty($dating[1])) ? $dating[1] : $attributes['date_earliest'];
	    if ($attributes['date_earliest'] == $attributes['date_latest']) {
	    	$attributes['dating'] = $dating[0];
	    } else {
	    	$attributes['dating'] = $dcTerms->created;
	    }
	    $attributes['medium'] = $dcElements->{'format.medium'}; // http://stackoverflow.com/questions/6531380/php-simplexml-with-dot-character-in-element-in-xml
	    $attributes['technique'] = $this->serialize($dcElements->format);
	    $attributes['inscription'] = $this->serialize($dcElements->description);
	    $attributes['state_edition'] =  (!empty($type[2])) ? $type[2] : null;
	    $attributes['gallery'] = $dcTerms->provenance;
	    $attributes['img_url'] = (!empty($identifier[1]) && (strpos($identifier[1], 'http') === 0)) ? $identifier[1] : null; //ak nieje prazdne a zacina 'http'

	    return $attributes;
    }

    private function serialize($attribute)
    {
    	return implode("; ", (array)$attribute);
    }

    private function starts_with_upper($str) {
	    $chr = mb_substr($str, 0, 1, "UTF-8");
	    return mb_strtolower($chr, "UTF-8") != $chr;
	}
}