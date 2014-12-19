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
		return View::make('harvests.form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();

		$rules = SpiceHarvesterHarvest::$rules;
		$v = Validator::make($input, $rules);

		if ($v->passes()) {
			
			$harvest = new SpiceHarvesterHarvest;
			$harvest->base_url = Input::get('base_url');
			$harvest->metadata_prefix = Input::get('metadata_prefix');
			$harvest->set_spec = Input::get('set_spec');
			$harvest->set_name = Input::get('set_name');
			$harvest->set_description = Input::get('set_description');
			$harvest->save();

			return Redirect::route('harvests.index');
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

	public function orphaned($id)
	{
		$processed_items = 0;
	    $removed_items = 0;
	    $timeStart = microtime(true);
        $start_from = null;

		$harvest = SpiceHarvesterHarvest::find($id);
		$client = new \Phpoaipmh\Client($harvest->base_url);
	    $myEndpoint = new \Phpoaipmh\Endpoint($client);

	    $items = Item::where('id', 'NOT LIKE', 'SVK:TMP%')->get();
	    $items_to_remove = array();

	    foreach ($items as $item) {
	    	$processed_items++;
	    	$remove_id = true;
	    	$rec = $myEndpoint->getRecord($item->id, $harvest->metadata_prefix);
	    	if (!empty($rec)) {	    		
		    	$setSpecs = (array) $rec->GetRecord->record->header->setSpec;
		    	// if ($setSpec==$harvest->set_spec) {
		    	if (in_array($harvest->set_spec, $setSpecs)) {
		    		$remove_id = false;		
		    	} 
	    	}
	    	if ($remove_id) {
	    		$items_to_remove[] = $item->id;
	    	}
	    }
	    
		$collections = Collection::lists('name', 'id');
		$items = Item::whereIn('id', $items_to_remove)->paginate('50');

		Session::flash('message', 'Našlo sa ' .count($items_to_remove). ' diel, ktoré sa už nenachádzajú v OAI sete ' . $harvest->set_name . ':');		
        return View::make('items.index', array('items' => $items, 'collections' => $collections));		

	}

	/**
	 * Launch the harvest process
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function launch($id)
	{
		$reindex = Input::get('reindex', false);
		$processed_items = 0;
	    $new_items = 0;
	    $updated_items = 0;
	    $skipped_items = 0;
	    $timeStart = microtime(true);

		$harvest = SpiceHarvesterHarvest::find($id);

        $start_from = null;

		if ($harvest->status == SpiceHarvesterHarvest::STATUS_COMPLETED && !$reindex) {
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
	    	
	    	if (!$this->isDeletedRecord($rec)) { //ak je v sete oznaceny ako zmazany

	    		//ak bol zmazany v tu v databaze, ale nachadza sa v OAI sete
	    		$is_deleted_record = SpiceHarvesterRecord::onlyTrashed()->where('identifier', '=', $rec->header->identifier)->count();
	    		if ($is_deleted_record > 0) {
	    			$skipped_items++;
	    		//inak insert alebo update
	    		} else {
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
	    }

	    $harvest->status = SpiceHarvesterHarvest::STATUS_COMPLETED;
	    $harvest->save();

	    $totalTime = round((microtime(true)-$timeStart));

	    Session::flash('message', 'Spracovaných bolo ' . $processed_items . ' diel. Z toho pribudlo ' . $new_items . ' nových diel,  ' . $updated_items . ' bolo upravených a ' . $skipped_items . ' bolo preskočených. Trvalo to ' . $totalTime . 's' );
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
        	$this->downloadImage($item, $itemAttributes['img_url']);
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

        // Upload image given by url
        if (!empty($itemAttributes['img_url'])) {
        	$this->downloadImage($item, $itemAttributes['img_url']);
        }        
        
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

	    $attributes['id'] = (string)$rec->header->identifier;
	    $attributes['identifier'] = (!empty($identifier[2])) ? $identifier[2] : '';	    
	    $attributes['title'] = $dcElements->title;
	    $attributes['author'] = $this->serialize($dcElements->creator);
	    $attributes['work_type'] = $type[0];
	    $attributes['work_level'] = $type[1];
	    $attributes['topic'] = $this->serialize($topic);
	    $attributes['subject'] = $this->serialize($subject);
	    $attributes['place'] = $this->serialize($dcElements->{'subject.place'});
	    // $trans = array(", " => ";", "šírka" => "", "výška" => "", "()" => "");
	    $trans = array(", " => ";", "; " => ";", "()" => "");
	    $attributes['measurement'] = trim(strtr($dcTerms->extent, $trans));
	    $dating = explode('/', $dcTerms->created[0]);
	    $dating_text = (!empty($dcTerms->created[1])) ? end((explode(', ', $dcTerms->created[1]))) : $dcTerms->created[0];
	    $attributes['date_earliest'] = (!empty($dating[0])) ? $dating[0] : null;
	    $attributes['date_latest'] = (!empty($dating[1])) ? $dating[1] : $attributes['date_earliest'];
	    $attributes['dating'] = $dating_text;
	    $attributes['medium'] = $dcElements->{'format.medium'}; // http://stackoverflow.com/questions/6531380/php-simplexml-with-dot-character-in-element-in-xml
	    $attributes['technique'] = $this->serialize($dcElements->format);
	    $attributes['inscription'] = $this->serialize($dcElements->description);
	    $attributes['state_edition'] =  (!empty($type[2])) ? $type[2] : null;
	    $attributes['gallery'] = $dcTerms->provenance;
	    $attributes['img_url'] = (!empty($identifier[1]) && (strpos($identifier[1], 'http') === 0)) ? $identifier[1] : null; //ak nieje prazdne a zacina 'http'

	    // $attributes['iipimg_url'] = NULL; // by default
	    if (!empty($identifier[3]) && (strpos($identifier[3], 'http') === 0)) {
	    	$iip_resolver = $identifier[3];
	    	$str = @file_get_contents($iip_resolver);
	    	if ($str != FALSE) {

		    	$str = strip_tags($str, '<br>'); //zrusi vsetky html tagy okrem <br>
				$iip_urls = explode('<br>', $str); //rozdeli do pola podla <br>
				asort($iip_urls); // zoradi pole podla poradia - aby na zaciatku boli predne strany (1_2, 2_2 ... )
				$iip_url = reset($iip_urls); // vrati prvy obrazok z pola - docasne - kym neumoznime viacero obrazkov k dielu
		    	if (str_contains($iip_url, '.jp2')) { //fix: vracia blbosti. napr linky na obrazky na webumenia. ber to vazne len ak odkazuje na .jp2
			    	$iip_url = substr($iip_url, strpos( $iip_url, '?FIF=')+5);
			    	$iip_url = substr($iip_url, 0, strpos( $iip_url, '.jp2')+4);
			    	$attributes['iipimg_url'] = $iip_url;	    		
		    	}
		    }
	    }
	    
	    // pretypovat SimpleXMLElement na string
	    foreach ($attributes as $key=>$attribute) {
	    	if (is_object($attribute)) {
	    		$attributes[$key] = (string) $attribute;
	    	}
	    }

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

	private function downloadImage($item, $img_url)
	{
    	$file = $img_url;
    	$data = file_get_contents($file);
    	$full = true;
     	if ($new_file = $item->getImagePath($full)) {
            file_put_contents($new_file, $data);
            return true;
     	}
     	return false;
	}
}