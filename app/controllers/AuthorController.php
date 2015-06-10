<?php

class AuthorController extends \BaseController {

	public function getIndex()
	{
		$per_page = 18;
		$page = \Input::get(Paginator::getPageName(), 1);
		$offset = ($page * $per_page) - $per_page;	

		$search = Input::get('search', null);
		$input = Input::all();


		if (Input::has('sort_by') && array_key_exists(Input::get('sort_by'), Authority::$sortable)) {
			$sort_by = Input::get('sort_by');
		} else {
			$sort_by = "items_with_images_count";
		}

		$sort_order = ($sort_by == 'name') ? 'asc' : 'desc';

		$params = array();
		$params["from"] = $offset;
		$params["size"] = $per_page;

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
			$params["sort"][] = "_score";
			// $params["sort"][] = ["created_at"=>["order"=>"desc"]];
			$params["sort"][] = ["$sort_by"=>["order"=>$sort_order]];
			$params["sort"][] = ["items_count"=>["order"=>"desc"]];
			$params["sort"][] = ["has_image"=>["order"=>"desc"]];
		}

		if (!empty($input)) {
			if (Input::has('search')) {
				$search = Input::get('search', '');
				$json_params = '
					{
					  "query": {
					  	"filtered" : {
					  	  "query": {
							  "bool": {
							    "should": [
							      { "match": {
							          "author.folded": {
							            "query": "'.$search.'",
							            "boost": 5
							          }
							        }
							      },

							      { "match": { "title":          "'.$search.'" }},
							      { "match": { "title.stemmed": "'.$search.'" }},
							      { "match": { 
							        "title.stemmed": { 
							          "query": "'.$search.'",  
							          "analyzer" : "slovencina_synonym" 
							        }
							      }
							      },

							      { "match": {
							          "subject.folded": {
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
					  },
					  "size": 100
					}
				';
				$params = json_decode($json_params, true);
			}

			foreach ($input as $filter => $value) {
				if (in_array($filter, Authority::$filterable) && !empty($value)) {
					$params["query"]["filtered"]["filter"]["bool"]["must"][]["term"][$filter] = $value;
				}
			}
            if(
            	!empty($input['year-range']) &&
            	$input['year-range']!=Authority::sliderMin().','.Authority::sliderMax() //nezmenena hodnota
            ) {
            	$range = explode(',', $input['year-range']);
            	$params["query"]["filtered"]["filter"]["bool"]["should"][]["range"]["death_year"]["gte"] = (string)$range[0];
            	$params["query"]["filtered"]["filter"]["bool"]["should"][]["missing"]["field"] = "death_year";
            	$params["query"]["filtered"]["filter"]["bool"]["must"][]["range"]["birth_year"]["lte"] = (string)$range[1];
            }
            if(!empty($input['first-letter'])) {
            	$params["query"]["filtered"]["filter"]["bool"]["must"][]["prefix"]["name"] = $input['first-letter'];
            }
		} 

		Debugbar::addMeasure('now', LARAVEL_START, microtime(true));
		$authors = null;
		Debugbar::measure('Elastic-search', function() use (&$authors, $params) {			
			$authors = Authority::search($params);
		});
		$authors->load('roles');
		$paginator = Paginator::make($authors->all(), $authors->total(), $per_page);


		// $authors = Authority::listValues('author', $params);
		$roles = Authority::listValues('role', $params);
		$nationalities = Authority::listValues('nationality', $params);
		$places = Authority::listValues('place', $params);
		// dd($roles);
		return View::make('autori', array(
			'authors'=>$authors, 
			'search'=>$search, 
			'sort_by'=>$sort_by,
			'roles'=>$roles, 
			'nationalities'=>$nationalities, 
			'places'=>$places, 
			'input'=>$input, 
			'paginator'=>$paginator, 
			));
	}

	public function getSuggestions()
	{
	 	$q = (Input::has('search')) ? str_to_alphanumeric(Input::get('search')) : 'null';

		$result = Elastic::search([
	        	'type' => Authority::ES_TYPE,
	        	'body'  => array(
	                'query' => array(
	                    'multi_match' => array(
	                        'query'  	=> $q,
	                        'type' 		=> 'cross_fields',
							// 'fuzziness' =>  2,
							// 'slop'		=>  2,
        	                'fields' 	=> array("name.suggest", "alternative_name.suggest"),
	                        'operator' 	=> 'and'
	                    ),
	                ),
	                'size' => '10',
	                'sort' => [
	                	'items_count' => ['order' => 'desc'],
	                	'has_image' => ['order' => 'desc'],
	                ]
	            ),        	
	      	]);

		$data = array();
		$data['results'] = array();
		$data['count'] = 0;
		
		// $data['items'] = array();
		foreach ($result['hits']['hits'] as $key => $hit) {

			$name = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $hit['_source']['name']);

			$data['count']++;
			$params = array(
				'id' => $hit['_id'],
				'name' => $name,
				'birth_year' => $hit['_source']['birth_year'],
				'death_year' => $hit['_source']['death_year'],
				'image' => Authority::getImagePathForId($hit['_id'], $hit['_source']['has_image'], $hit['_source']['sex'],  false, 70)
			);
			$data['results'][] = array_merge($params) ;
		}

	    return Response::json($data);	
	}

	public function getDetail($id)
	{
		$author = Authority::find($id);
		if (empty($author)) {
			App::abort(404);
		}
		$author->view_count += 1; 
		$author->save();

		return View::make('autor', array('author'=>$author));

	}


}