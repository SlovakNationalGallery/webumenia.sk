<?php

class CatalogController extends \BaseController {

	public function getIndex()
	{
		$search = Input::get('search', null);
		$input = Input::all();

		//ak zada presne ID
		if (strpos($search, ':')!==false) {
			$item = Item::find($search);
			if ($item) {
				return Redirect::to($item->getDetailUrl());
			}
		}

		if (Input::has('sort_by') && array_key_exists(Input::get('sort_by'), Item::$sortable)) {
			$sort_by = Input::get('sort_by');
		} else {
			$sort_by = "created_at";
		}

		$sort_order = ($sort_by == 'author' || $sort_by == 'title') ? 'asc' : 'desc';

		$per_page = 18;
		$page = \Input::get(Paginator::getPageName(), 1);
		$offset = ($page * $per_page) - $per_page;

		$params = array();
		$params["from"] = $offset;
		$params["size"] = $per_page;

		if (!Input::has('sort_by') || $sort_by=='created_at') {
			$params["sort"][] = "_score";
			$params["sort"][] = ["has_image"=>["order"=>"desc"]];
			$params["sort"][] = ["has_iip"=>["order"=>"desc"]];
			$params["sort"][] = ["created_at"=>["order"=>"desc"]];
		} else {
			if ($sort_by=='random') {
				$random = json_decode('
					{"_script": {
					    "script": "Math.random() * 200000",
					    "type": "number",
					    "params": {},
					    "order": "asc"
					 }}', true);
				$params["sort"][] = $random;			
			} else {
				$params["sort"][] = ["$sort_by"=>["order"=>"$sort_order"]];	
			}
		}

		if (!empty($input)) {
			
			if (Input::has('search')) {
				$search = str_to_alphanumeric(Input::get('search', ''));
				$json_params = '
					{
					  "query": {
					  	"filtered" : {
					  	  "query": {
							  "bool": {
							    "should": [

							      { "match": {
							          "identifier": {
							            "query": "'.$search.'",
							            "boost": 10
							          }
							        }
							      },

							      { "match": {
							          "author.folded": {
							            "query": "'.$search.'",
							            "boost": 5
							          }
							        }
							      },

							      { "match": { "title":          "'.$search.'" }},
							      { "match": { "title.folded":          "'.$search.'" }},
							      { "match": { "title.stemmed": "'.$search.'" }},
							      { "match": { 
							        "title.stemmed": { 
							          "query": "'.$search.'",  
							          "analyzer" : "slovencina_synonym" 
							        }
							      }
							      },

							      { "match": {
							          "tag.folded": {
							            "query": "'.$search.'",
							            "boost": 1
							          }
							        }
							      },
							      { "match": {
							          "tag.stemmed": {
							            "query": "'.$search.'",
							            "boost": 1
							          }
							        }
							      },

							      { "match": {
							          "description": {
							            "query": "'.$search.'",
							            "boost": 1
							          }
							        }
							      },
							      { "match": {
							          "description.stemmed": {
							            "query": "'.$search.'",
							            "boost": 0.9
							          }
							        }
							      },
							      { "match": {
							          "description.stemmed": {
							            "query": "'.$search.'",
							            "analyzer" : "slovencina_synonym",
							            "boost": 0.5
							          }
							        }
							      },

							      { "match": {
							          "place.folded": {
							            "query": "'.$search.'",
							            "boost": 1
							          }
							        }
							      }


							    ]
							  }
							}
						}
					  }
					}
				';

				$params = array_merge($params, json_decode($json_params, true));

			}

			foreach ($input as $filter => $value) {
				if (in_array($filter, Item::$filterable) && !empty($value)) {
					$params["query"]["filtered"]["filter"]["and"][]["term"][$filter] = $value;
				}
			}
            if(
            	!empty($input['year-range']) &&
            	$input['year-range']!=Item::sliderMin().','.Item::sliderMax() //nezmenena hodnota
            ) {
            	$range = explode(',', $input['year-range']);
            	$params["query"]["filtered"]["filter"]["and"][]["range"]["date_earliest"]["gte"] = $range[0];
            	$params["query"]["filtered"]["filter"]["and"][]["range"]["date_latest"]["lte"] = $range[1];
            }
			
		} 
		$items = Item::search($params);
		$paginator = Paginator::make($items->all(), $items->total(), $per_page);

		$authors = Item::listValues('author', $params);
		$work_types = Item::listValues('work_type', $params);
		$tags = Item::listValues('tag', $params);
		$galleries = Item::listValues('gallery', $params);
		$topics = Item::listValues('topic', $params);
		$techniques = Item::listValues('technique', $params);
		

		$queries = DB::getQueryLog();
		$last_query = end($queries);

		return View::make('katalog', array(
			'items'=>$items, 
			'authors'=>$authors, 
			'work_types'=>$work_types, 
			'tags'=>$tags, 
			'galleries'=>$galleries, 
			'topics'=>$topics, 
			'techniques'=>$techniques, 
			'search'=>$search, 
			'sort_by'=>$sort_by,
			'input'=>$input, 
			'paginator'=>$paginator, 
			));
	}

	public function getSuggestions()
	{
	 	$q = (Input::has('search')) ? str_to_alphanumeric(Input::get('search')) : 'null';

		$result = Elastic::search([
	        	'type' => Item::ES_TYPE,
	        	'body'  => array(
	                'query' => array(
	                    'multi_match' => array(
	                        'query'  	=> $q,
	                        'type' 		=> 'cross_fields',
							// 'fuzziness' =>  2,
							// 'slop'		=>  2,
        	                'fields' 	=> array("identifier", "title.suggest", "author.suggest"),
	                        'operator' 	=> 'and'
	                    ),
	                ),
	                'size' => '10',
	            ),        	
	      	]);

		$data = array();
		$data['results'] = array();
		$data['count'] = 0;
		
		// $data['items'] = array();
		foreach ($result['hits']['hits'] as $key => $hit) {

			$authors = array();
			foreach ($hit['_source']['author'] as $author) {
				$authors[] = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $author);
			}

			$data['count']++;
			$params = array(
				'id' => $hit['_id'],
				'title' => $hit['_source']['title'],
				'author' => $authors,
				'image' => Item::getImagePathForId($hit['_id'], false, 70)
			);
			$data['results'][] = array_merge($params) ;
		}

	    return Response::json($data);	
	}


}