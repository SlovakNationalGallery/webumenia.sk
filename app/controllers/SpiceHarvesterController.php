<?php

class SpiceHarvesterController extends \BaseController {

    const OAI_DATE_FORMAT = 'Y-m-d';

    const METADATA_SCHEMA = 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd';
    const METADATA_PREFIX = 'oai_dc';

    const OAI_DC_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';
    const DUBLIN_CORE_NAMESPACE_ELEMTS = 'http://purl.org/dc/elements/1.1/';
    const DUBLIN_CORE_NAMESPACE_TERMS = 'http://purl.org/dc/terms/';

    protected $exclude_prefix = array('x', 'z');
    protected $log;

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$harvests = SpiceHarvesterHarvest::orderBy('created_at', 'DESC')->paginate(10);
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
			$harvest->type = Input::get('type');
			$harvest->metadata_prefix = Input::get('metadata_prefix');
			$harvest->set_spec = Input::get('set_spec');
			$harvest->set_name = Input::get('set_name');
			$harvest->set_description = Input::get('set_description');
			$collection = Collection::find(Input::get('collection_id'));
			if ($collection) $harvest->collection()->associate($collection);			
			if (Input::has('username') && Input::has('password')) {
				$harvest->username = Input::get('username');
				$harvest->password = Input::get('password');				
			}
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
		$harvest = SpiceHarvesterHarvest::find($id);
		$harvest->load('collection');
        return View::make('harvests.show')->with('harvest', $harvest);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$harvest = SpiceHarvesterHarvest::find($id);

		if(is_null($harvest))
		{
			return Redirect::route('harvest.index');
		}

        return View::make('harvests.form')->with('harvest', $harvest);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$v = Validator::make(Input::all(), SpiceHarvesterHarvest::$rules);

		if($v->passes())
		{
			$input = array_except(Input::all(), array('_method'));

			$harvest = SpiceHarvesterHarvest::find($id);
			$harvest->base_url = Input::get('base_url');
			$harvest->type = Input::get('type');
			if (Input::has('username') && Input::has('password')) {
				$harvest->username = Input::get('username');
				$harvest->password = Input::get('password');				
			}
			$harvest->metadata_prefix = Input::get('metadata_prefix');
			$harvest->set_spec = Input::get('set_spec');
			$harvest->set_name = Input::get('set_name');
			$harvest->set_description = Input::get('set_description');
			// $collection = Collection::find(Input::get('collection_id'));
			// if ($collection->count()) $harvest->collection()->associate($collection);
			$harvest->collection_id = Input::get('collection_id');
			$harvest->save();

			Session::flash('message', 'Harvest <code>'.$harvest->set_spec.'</code> bol upravený');
			return Redirect::route('harvests.index');
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
		$harvest = SpiceHarvesterHarvest::find($id);
		$set_spec = $harvest->set_spec;
		foreach ($harvest->records as $i => $record) {
			Item::destroy($record->item_id);
			$record->delete();
		}		
		$harvest->delete();
		return Redirect::route('harvests.index')->with('message', 'Harvest <code>'.$set_spec.'</code>  bol zmazaný');;
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

	    $items_to_remove = array();

	    foreach ($harvest->records as $i => $record) {
	    	$processed_items++;
	    	$remove_id = true;
	    	$rec = $myEndpoint->getRecord($record->item_id, $harvest->metadata_prefix);
	    	if (!empty($rec)) {	    		
		    	$setSpecs = (array) $rec->GetRecord->record->header->setSpec;
		    	// if ($setSpec==$harvest->set_spec) {
		    	if (in_array($harvest->set_spec, $setSpecs)) {
		    		$remove_id = false;		
		    	} 
	    	}
	    	if ($remove_id) {
	    		$items_to_remove[] = $record->item_id;
	    	}
	    }
	    
		$collections = Collection::lists('name', 'id');
		if (count($items_to_remove)) {
			$items = Item::whereIn('id', $items_to_remove)->paginate('50');
		} else {
			$items = Item::where('id','=',0);
		}
		Session::flash('message', 'Našlo sa ' .count($items_to_remove). ' záznamov, ktoré sa už nenachádzajú v OAI sete ' . $harvest->set_name . ':');		
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
		$logFile = 'oai_harvest.log';
		$this->log = new Monolog\Logger('oai_harvest');
		$this->log->pushHandler(new Monolog\Handler\StreamHandler(storage_path().'/logs/'.$logFile, Monolog\Logger::WARNING));

		Debugbar::disable();
		$reindex = Input::get('reindex', false);
		$processed_items = 0;
	    $new_items = 0;
	    $updated_items = 0;
	    $skipped_items = 0;
	    $timeStart = microtime(true);

		$harvest = SpiceHarvesterHarvest::find($id);

        $start_from = null;
        // $start_from = new DateTime('2014-01-01'); //docasne

		if (($harvest->status == SpiceHarvesterHarvest::STATUS_COMPLETED || $harvest->status == SpiceHarvesterHarvest::STATUS_IN_PROGRESS) && !$reindex) {
            $start_from = new DateTime($harvest->completed);
            // $start_from->sub(new DateInterval('P1D'));
        } 

		$harvest->status = $harvest::STATUS_QUEUED;
		$harvest->initiated = date('Y-m-d H:i:s');
		$harvest->save();


		$harvest->status = $harvest::STATUS_IN_PROGRESS;
		$harvest->status_messages = '';
		$harvest->save();

	    $myEndpoint = $this->getEndpoint($harvest); 

    	$recs = $myEndpoint->listRecords($harvest->metadata_prefix, $start_from, null, $harvest->set_spec);
    	if (App::runningInConsole()) {
    		echo "celkovy pocet: ". $recs->getTotalRecordsInCollection() . "\n";
    	}
	    $dt = new \DateTime;

	    $collection = $harvest->collection;

	    try {
	    foreach($recs as $rec) {
	    	$processed_items++;
	    	if (App::runningInConsole()) {
		    	if ($processed_items % 100 == 0) echo date('h:i:s'). " " . $processed_items . "\n";
		    }

	    	if (!$this->isDeletedRecord($rec) && !$this->isExcludedRecord($rec)) { //ak je v sete oznaceny ako zmazany

	    		//ak bol zmazany v tu v databaze, ale nachadza sa v OAI sete
	    		$rec_id = (string)$rec->header->identifier;
	    		$is_deleted_record = SpiceHarvesterRecord::onlyTrashed()->where('identifier', '=', $rec_id)->where('type', '=', $harvest->type)->count();
	    		if ($is_deleted_record > 0) {
	    			$skipped_items++;
	    		//inak insert alebo update
	    		} else {
					$existingRecord = SpiceHarvesterRecord::where('identifier', '=', $rec_id)->where('type', '=', $harvest->type)->first();

			        if ($existingRecord) {	    			
			            // ak sa zmenil datestamp, update item - inak ignorovat
			            // if( $existingRecord->datestamp != $rec->header->datestamp) {
			                if ($this->updateRecord($existingRecord, $rec, $harvest->type)) {
			                	$updated_items++;	
			                } else {
			                	$skipped_items++;
			                }
			                
			            // }
			        } else {
			            if ($this->insertRecord($id, $rec, $harvest->type)) {
			            	$new_items++;	
			            } else {
			            	$skipped_items++;
			            }
			            
			        }

			        // ak je zvolena kolekcia - hned do nej pridat
			        if ($harvest->collection) {
			        	$collection->items()->sync([$rec_id], false);
			        }
			    }

   	    	}
	    }
        } catch (\Phpoaipmh\Exception\MalformedResponseException $e) {
            // $harvest->status = SpiceHarvesterHarvest::STATUS_ERROR; 
            // tuto chybu vrati, ak ziadne records niesu - cize harvest moze pokracovat dalej
            $harvest->status_messages = $e->getMessage() . "\n";
        }

	    $totalTime = round((microtime(true)-$timeStart));
	    $message = 'Spracovaných bolo ' . $processed_items . ' záznamov. Z toho pribudlo ' . $new_items . ' nových záznamov,  ' . $updated_items . ' bolo upravených a ' . $skipped_items . ' bolo preskočených. Trvalo to ' . $totalTime . 's';

	    $harvest->status = SpiceHarvesterHarvest::STATUS_COMPLETED;
	    $harvest->status_messages .= $message;
		$harvest->completed = date('Y-m-d H:i:s');
	    $harvest->save();

	    if (App::runningInConsole()) {
			echo $message . "\n"; return true;	
		}

	    Session::flash('message', $message);
	    return Redirect::route('harvests.index');
	}

	public function refreshRecord($record_id)
	{
		$record = SpiceHarvesterRecord::find($record_id);
		$harvest = $record->harvest;
		$myEndpoint = $this->getEndpoint($harvest); 
		$rec = $myEndpoint->getRecord($record->item_id, $harvest->metadata_prefix);
		// $vendorDir = base_path() . '/vendor'; include($vendorDir . '/imsop/simplexml_debug/src/simplexml_dump.php'); include($vendorDir . '/imsop/simplexml_debug/src/simplexml_tree.php');
		// simplexml_tree($rec->GetRecord->record);  dd();
		// foreach($recs->GetRecord as $rec) {
			$this->updateRecord($record, $rec->GetRecord->record, $harvest->type);
		// }
		$message = 'Pre záznam boli úspešne načítané dáta z OAI';
		Session::flash('message', $message);
		return Redirect::back();
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
     * Return whether the record ID is excluded from harvest
     * 
     * @param SimpleXMLIterator The record object
     * @return bool
     */
    private function isExcludedRecord($rec)
    {
        $rec_id = (string)$rec->header->identifier;
        $rec_id_porefix = substr($rec_id, strpos( $rec_id, '.')+1, 1);
        $rec_id_porefix = mb_strtolower($rec_id_porefix, "UTF-8");
        if ( in_array($rec_id_porefix, $this->exclude_prefix) ) {
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
    private function insertRecord($harvest_id, $rec, $type) {

    	switch ($type) {
    		case 'item':
		    	$attributes = $this->mapItemAttributes($rec);
		    	if(isSet($attributes['publish']) && $attributes['publish']==0) return false;
		    	$item = Item::updateOrCreate(['id' => $attributes['id']], $attributes);
			    $item->authorities()->sync($attributes['authority_ids']);
    			break;
    		case 'author':
		    	// $nationality = Nationality::firstOrNew(['id' => ])
		    	$attributes = $this->mapAuthorAttributes($rec);
			    $author = Authority::updateOrCreate(['id' => $attributes['id']], $attributes);
			    if (!empty($attributes['nationalities'])) {
			    	$nationality_ids = array();
				    foreach ($attributes['nationalities'] as $key => $nationality) {
				    	$nationality = Nationality::firstOrCreate($nationality);
				    	$nationality_ids[] = $nationality['id'];
				    }
				    $nationality = $author->nationalities()->sync($nationality_ids);
				}
			    if (!empty($attributes['roles'])) {
				    foreach ($attributes['roles'] as $key => $role) {
				    	$role['authority_id'] = $author->id;
				    	$role = AuthorityRole::firstOrCreate($role);
				    }
				}
			    if (!empty($attributes['names'])) {
				    foreach ($attributes['names'] as $key => $name) {
				    	$name['authority_id'] = $author->id;
				    	$name = AuthorityName::firstOrCreate($name);
				    }
				}
			    if (!empty($attributes['events'])) {
				    foreach ($attributes['events'] as $key => $event) {
				    	$event['authority_id'] = $author->id;
				    	$event = AuthorityEvent::firstOrCreate($event);
				    }
				}
			    if (!empty($attributes['relationships'])) {
				    foreach ($attributes['relationships'] as $key => $relationship) {
				    	$relationship['authority_id'] = $author->id;
				    	$relationship = AuthorityRelationship::firstOrCreate($relationship);
				    }
				}
			    if (!empty($attributes['links'])) {
				    foreach ($attributes['links'] as $key => $url) {
				    	$link = new Link();
				    	$link->url = $url;
				    	$url_parts = parse_url($url);
				    	$link->label = $url_parts['host'];
				    	$author->links()->save($link);
				    }
				}
    			break;
    	}

		// Insert the record after the item is saved
	    $record = new SpiceHarvesterRecord();
	    $record->harvest_id = $harvest_id;
	    $record->type = $type;
	    $record->item_id = $attributes['id'];
	    $record->identifier = $rec->header->identifier;
	    $record->datestamp = $rec->header->datestamp;
	    $record->save();

      
        // Upload image given by url
        if (!empty($attributes['img_url'])) {
        	$this->downloadImage($item, $attributes['img_url']);
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
    private function updateRecord($existingRecord, $rec, $type) {
    	// Update the item
    	switch ($type) {
    		case 'item':
		    	$attributes = $this->mapItemAttributes($rec);
		    	if(isSet($attributes['publish']) && $attributes['publish']==0) return false;
			    // $item = Item::where('id', '=', $rec->header->identifier)->first();
			    $item = Item::firstOrCreate(['id' => $rec->header->identifier]);
			    $item->fill($attributes);
			    $item->authorities()->sync($attributes['authorities']);
			    $item->save();
    			break;
    		case 'author':
		    	$attributes = $this->mapAuthorAttributes($rec);
    			//if (6478 == $rec->header->identifier) dd($attributes);
		    	unset($attributes['biography']); //neprepisovat biografiu - chceme nechat tu co sme rucne vyplnili
			    $author = Authority::where('id', '=', $rec->header->identifier)->first();
			    $author->fill($attributes);
				if (!empty($attributes['nationalities'])) {
			    	$nationality_ids = array();
				    foreach ($attributes['nationalities'] as $key => $nationality) {
				    	$nationality = Nationality::firstOrCreate($nationality);
				    	$nationality_ids[] = $nationality['id'];
				    }
				    $nationality = $author->nationalities()->sync($nationality_ids);
				}
				$roles_to_remove = $this->get_obsolete_attributes($author->roles->lists('role', 'id'), $attributes['roles']);
				AuthorityRole::destroy(array_keys($roles_to_remove));
				if (!empty($attributes['roles'])) {
				    foreach ($attributes['roles'] as $key => $role) {
				    	$role['authority_id'] = $author->id;
				    	$role = AuthorityRole::firstOrCreate($role);
				    }
				}				
			    if (!empty($attributes['names'])) {
				    foreach ($attributes['names'] as $key => $name) {
				    	$name['authority_id'] = $author->id;
				    	$name = AuthorityName::firstOrCreate($name);
				    }
				}
				$events_to_remove = $this->get_obsolete_attributes($author->events->lists('event', 'id'), $attributes['events']);
				AuthorityEvent::destroy(array_keys($events_to_remove));
				if (!empty($attributes['events'])) {
				    foreach ($attributes['events'] as $key => $event) {
				    	$event['authority_id'] = $author->id;
				    	$event = AuthorityEvent::updateOrCreate(['id'=>$event['id']], $event);
				    }
				}
			    if (!empty($attributes['relationships'])) {
				    foreach ($attributes['relationships'] as $key => $relationship) {
				    	$related_autrhority = Authority::where('id', '=', $relationship['related_authority_id'])->first();
				    	if (!is_null($related_autrhority)) {
				    		$relationship['authority_id'] = $author->id;
				    		// $relationship['name'] = $related_autrhority->name;
				    		$authority_relationship = AuthorityRelationship::firstOrCreate($relationship);				    		
				    	}
				    }
				}
				if (!empty($attributes['links'])) {
				    foreach ($attributes['links'] as $key => $url) {
				    	// dd($url);
				    	if(Link::where('url', '=', $url)->where('linkable_id', '=', $author->id)->count()==0){
					    	$link = new Link();
					    	$link->url = $url;
					    	$link->label = Link::parse($url);
					    	$author->links()->save($link);
					    }
				    }
				}
			    $author->save();
    			break;
    	}

        // Upload image given by url
        if (!empty($attributes['img_url'])) {
        	$this->downloadImage($item, $attributes['img_url']);
        }        
        
        // Update the datestamp stored in the database for this record.
	    $existingRecord->datestamp = $rec->header->datestamp;
	    // $existingRecord->updated_at =  date('Y-m-d H:i:s'); //toto by sa malo diat automaticky
	    $existingRecord->save();


        return true;
    }

    /**
     * Map attributes from OAI to author schema
     */
    private function mapAuthorAttributes($rec)
    {
		// $vendorDir = base_path() . '/vendor'; include($vendorDir . '/imsop/simplexml_debug/src/simplexml_dump.php'); include($vendorDir . '/imsop/simplexml_debug/src/simplexml_tree.php');

    	$attributes = array();
		// simplexml_tree($rec);  dd();
		$rec->registerXPathNamespace('cedvu', 'http://www.webumenia.sk#');
		$rec->registerXPathNamespace('ulan', 'http://e-culture.multimedian.nl/ns/getty/ulan');
		$rec->registerXPathNamespace('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns');
		$rec->registerXPathNamespace('vp', 'http://e-culture.multimedian.nl/ns/getty/vp');
		$metadata = $rec->metadata->children('cedvu', true)->Vocabulary->children('vp', true)->Subject;

		$attributes['id'] = (int)$this->parseId((string)$metadata->attributes('rdf', true)->about);
		$attributes['type'] = mb_strtolower((string)$metadata->Record_Type, "UTF-8");
		$attributes['type_organization'] = (string)$metadata->Record_Type_Organization;
		$attributes['name'] = (string)$metadata->attributes('vp', true)->labelPreferred;
		$attributes['sex'] = mb_strtolower((string)$metadata->Biographies->Preferred_Biography->Sex, "UTF-8");
		$biography = $this->parseBiography((string)$metadata->Biographies->Preferred_Biography->Biography_Text);
		if (strpos($biography, 'http')!== false) {			
			preg_match_all('!https?://\S+!', $biography, $matches);
			$attributes['links']= $matches[0];
			$biography = ''; // vymazat bio
		}
		$attributes['biography'] = $biography;
		if (!empty($metadata->Biographies->Preferred_Biography->Birth_Place))
			$attributes['birth_place'] = $this->trimAfter((string)$metadata->Biographies->Preferred_Biography->Birth_Place);
		if (!empty($metadata->Biographies->Preferred_Biography->Birth_Date))
			$attributes['birth_year'] = $this->parseYear($metadata->Biographies->Preferred_Biography->Birth_Date);
			$attributes['birth_date'] = (string)$metadata->Biographies->Preferred_Biography->Birth_Date;
		if (!empty($metadata->Biographies->Preferred_Biography->Death_Place))
			$attributes['death_place'] = $this->trimAfter((string)$metadata->Biographies->Preferred_Biography->Death_Place);
		if (!empty($metadata->Biographies->Preferred_Biography->Death_Date))
			$attributes['death_year'] = $this->parseYear($metadata->Biographies->Preferred_Biography->Death_Date);
			$attributes['death_date'] = (string)$metadata->Biographies->Preferred_Biography->Death_Date;
		$attributes['nationalities'] = array();
		foreach ($metadata->Nationalities->Preferred_Nationality as $key => $nationality) {
			$attributes['nationalities'][] = [
				'id' => (int)$this->parseId((string)$nationality->attributes('rdf', true)->resource),
				'code' => (string)$nationality->Nationality_Code,
				// 'prefered' => true,
			];
		}
		$attributes['roles'] = array();
		foreach ($metadata->Roles->Preferred_Role as $key => $role) {
			$attributes['roles'][] = [
				'role' => $this->trimAfter((string)$role->Role_ID),
				// 'prefered' => true,
			];
		}
		$attributes['names'] = array();
		// * preferovane nepridavame - ukladame len "alternative names" *
		// foreach ($metadata->Terms->Preferred_Term as $key => $term) {
		// 	$attributes['names'][] = [
		// 		'name' => (string)$term->Term_Text,
		// 		'prefered' => true,
		// 	];
		// }
		foreach ($metadata->Terms->{'Non-Preferred_Term'} as $key => $term) {
			$attributes['names'][] = [
				'name' => (string)$term->Term_Text,
				'prefered' => false,
			];
		}
		$attributes['events'] = array();
		foreach ($metadata->Events->{'Non-Preferred_Event'} as $key => $event) {
			$attributes['events'][] = [
				'id' => (int)$event->attributes('rdf', true)->resource,
				'event' => (string)$event->Event_ID,
				'place' => $this->trimAfter((string)$event->Place),
				'prefered' => false,
				'start_date' => (string)$event->Event_Date->Start_Date,
				'end_date' => (string)$event->Event_Date->End_Date,
			];
		}
		$attributes['relationships'] = array();
		foreach ($metadata->Associative_Relationships->Associative_Relationship as $key => $relationship) {
			$related_authority_id = (int)$this->parseId((string)$relationship->Related_Subject_ID);
			if ($related_authority_id) {
				$attributes['relationships'][$related_authority_id] = [
					'type' => (string)$relationship->Relationship_Type,
					'related_authority_id' => $related_authority_id
				];				
			}
		}

	    return $attributes;
    }

    /**
     * Map attributes from OAI to item schema
     */
    private function mapItemAttributes($rec)
    {
		// $vendorDir = base_path() . '/vendor'; include($vendorDir . '/imsop/simplexml_debug/src/simplexml_dump.php'); include($vendorDir . '/imsop/simplexml_debug/src/simplexml_tree.php');

    	$attributes = array();

		$dcElements = $rec->metadata
	                    ->children(self::OAI_DC_NAMESPACE)
	                    ->children(self::DUBLIN_CORE_NAMESPACE_ELEMTS);


		$dcTerms = $rec->metadata
	                    ->children(self::OAI_DC_NAMESPACE)
	                    ->children(self::DUBLIN_CORE_NAMESPACE_TERMS);

	    $type = (array)$dcElements->type;
	    $identifiers = (array)$dcElements->identifier;

	    $topic=array(); // zaner - krajina s figuralnou kompoziciou / veduta
	    $subject=array(); // objekt - dome/les/

	try {

	    foreach ($dcElements->subject as $key => $value) {
	    	if ($this->starts_with_upper($value)) {
	    		$subject[] = mb_strtolower($value, "UTF-8");
	    	} else {
	    		$topic[] =$value;
	    	}
	    }

	    foreach ($identifiers as $identifier) {
	    	if ($identifier!=(string)$rec->header->identifier) {
	    		//identifikator
	    		if ($this->starts_with_upper($identifier)) {
	    			$attributes['identifier'] = $identifier;  
	    		} elseif (strpos($identifier,'getimage') !== false) {
	    			$attributes['img_url'] = $identifier;  
	    		} elseif (strpos($identifier,'L2_WEB') !== false) {
	    			$attributes['iipimg_url'] = $this->resolveIIPUrl($identifier);   		
	    		}
	    	}
	    	
	    }

	    $attributes['id'] = (string)$rec->header->identifier;
	    $attributes['title'] = $dcElements->title;
	    $authors = array();
	    $authority_ids = array();
	    $authorities = array();
	    foreach ($dcElements->creator as $key => $creator) {
		    if (strpos($creator, 'urn:')!==false) {
		    	$authority_ids[] = (int)$this->parseId($creator);
		    } else {
		    	$authors[] = (string)$creator;
		    }
	    }
	    $i = 0;
	    foreach ($dcElements->{'creator.role'} as $role) {
		    	$authorities[ $authority_ids[$i] ]['role'] = (string)$role;
		    	$authorities[ $authority_ids[$i] ]['name'] = $authors[$i];
		    	$i++;
	    }
	    $attributes['authorities'] = $authorities;
	    $attributes['authority_ids'] = $authority_ids;
	    $attributes['author'] = $this->serialize($authors);
	    if (!empty($type[0])) $attributes['work_type'] = $type[0];
	    if (!empty($type[1])) $attributes['work_level'] = $type[1];
	    
	    $attributes['topic'] = $this->serialize($topic);
	    $attributes['subject'] = $this->serialize($subject);
	    $attributes['place'] = $this->serialize($dcElements->{'subject.place'});
	    // $trans = array(", " => ";", "šírka" => "", "výška" => "", "()" => "");
	    $trans = array(", " => ";", "; " => ";", "()" => "");
	    $attributes['measurement'] = trim($dcTerms->extent);
	    // $attributes['measurement'] = trim(strtr($dcTerms->extent, $trans));
	    // dd($attributes['measurement']);
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
	    if (isSet($dcElements->rights[0])) $attributes['publish'] = (int)$dcElements->rights[0];
		$related = (string)$dcElements->{'relation.isPartOf'};
		$related_parts = explode(':', $related);
		$attributes['relationship_type'] = array_shift($related_parts);
		if($related_parts) {
			$attributes['related_work'] = trim(preg_replace('/\s*\([^)]*\)/', '', $related_parts[0]));
			preg_match('#\((.*?)\)#',  $related_parts[0], $match); 
			if (isSet($match[1])) {
				$related_work_order = $match[1];
				$related_work_order_parts = explode('/', $related_work_order);
				$related_work_order = array_shift($related_work_order_parts);
				$related_work_total = array_shift($related_work_order_parts);
				if (!is_numeric($related_work_order)) {
					$attributes['related_work'] = $related_work_order;
				} else {
					$attributes['related_work_order'] = (int)$related_work_order;
					$attributes['related_work_total'] = (int)$related_work_total;
				}
			}
		}
	} catch (Exception $e) {
		Log::error('Identifier: ' . $identifier);
		Log::error('Message: ' . $e->getMessage());
		die('nastala chyba. pozri log.');
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
    	try {
    		$data = file_get_contents($file);
    	} catch (Exception $e) {
    		$this->log->addError($img_url . ': ' . $e->getMessage());	
    		return false;
    	}
    	
    	$full = true;
     	if ($new_file = $item->getImagePath($full)) {
            file_put_contents($new_file, $data);
            return true;
     	}
     	return false;
	}

	private function parseId ( $string, $delimiter=':' )
	{
	    return substr($string, strrpos($string, $delimiter) + ( (strrpos($string, $delimiter)!== false) ? strlen($delimiter) : 0));
	}

	private function trimAfter ( $string, $delimiter='/' )
	{
	    $parts = explode($delimiter, $string);
	    return $parts[0];
	    // return substr($string, 0, strpos($string, $delimiter));
	}

	private function parseBiography ( $string )
	{
	    $bio = $this->parseId($string, '(ZNÁMY)');
	    return (!empty($bio)) ? $bio : '';
	}

	private function parseDate ( $string )
	{
	    $result = null;
	    if (substr_count($string, '.')==2) {
	    	if ($date = DateTime::createFromFormat('d.m.Y|', $string))
	    		$result = $date->format('Y-m-d');
	    }
	    return $result;
	}

	private function parseYear ( $string )
	{
	    return (int)end((explode('.', $string)));
	}

	private function resolveIIPUrl( $iip_resolver )
	{
    	$str = @file_get_contents($iip_resolver);
    	if ($str != FALSE) {

	    	$str = strip_tags($str, '<br>'); //zrusi vsetky html tagy okrem <br>
			$iip_urls = explode('<br>', $str); //rozdeli do pola podla <br>
			asort($iip_urls); // zoradi pole podla poradia - aby na zaciatku boli predne strany (1_2, 2_2 ... )
			$iip_url = reset($iip_urls); // vrati prvy obrazok z pola - docasne - kym neumoznime viacero obrazkov k dielu
	    	if (str_contains($iip_url, '.jp2')) { //fix: vracia blbosti. napr linky na obrazky na webumenia. ber to vazne len ak odkazuje na .jp2
		    	$iip_url = substr($iip_url, strpos( $iip_url, '?FIF=')+5);
		    	$iip_url = substr($iip_url, 0, strpos( $iip_url, '.jp2')+4);
		    	return $iip_url;	    		
	    	}
	    }
	    return null;
	}

	private function processUrl($url) {
		return str_replace('www.webumenia', 'stary.webumenia', $url);
	}

	private function getEndpoint($harvest)
	{
		//--- nazacat samostatnu metodu
		$guzzleAdapter = null;
		if ($harvest->username && $harvest->password) {
			$gclient = new GuzzleHttp\Client(['defaults' =>  ['auth' =>  [$harvest->username, $harvest->password]]]);
			$guzzleAdapter = new \Phpoaipmh\HttpAdapter\GuzzleAdapter($gclient);			
		}
		$client = new \Phpoaipmh\Client($harvest->base_url, $guzzleAdapter);
		$endpoint = new \Phpoaipmh\Endpoint($client);		
		return $endpoint;
	}

	/**
	 * return array(ids to remove)
	 */
	private function get_obsolete_attributes($db_array, $oai_array) {
		//first in array is id/or value to check
		foreach ($db_array as $id=>$db_attribute) {
			foreach ($oai_array as $oai_attribute) {
				if (in_array($db_attribute, $oai_attribute)) {
					unset($db_array[$id]);
				}
			}
		}

		return $db_array;
	}

}