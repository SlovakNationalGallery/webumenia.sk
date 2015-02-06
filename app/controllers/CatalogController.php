<?php

class CatalogController extends \BaseController {

	public function index()
	{
		$search = Input::get('search', null);
		$input = Input::all();
		// dd($input);

		$authors = Item::listValues('author');
		$work_types = Item::listValues('work_type', ',', true);
		$tags = Item::listValues('subject');
		$galleries = Item::listValues('gallery');

		
		if (Input::has('search')) {
			$search = Input::get('search', '');
			$params = [
			    'query' => [
			        'match' => [
			            '_all' => $search
			        ]
			    ],
			    'size' => 100
			];

			$items = Item::search($params)->paginate(18);

		} else {

			$items = Item::where(function($query) use ($search, $input) {
	                /** @var $query Illuminate\Database\Query\Builder  */
	                if (!empty($search)) {
	                	$query->where('title', 'LIKE', '%'.$search.'%')->orWhere('author', 'LIKE', '%'.$search.'%')->orWhere('subject', 'LIKE', '%'.$search.'%')->orWhere('id', 'LIKE', '%'.$search.'%');
	                }
	                if(!empty($input['author'])) {
	                	$query->where('author', 'LIKE', '%'.$input['author'].'%');
	                }
	                if(!empty($input['work_type'])) {
	                	// dd($input['work_type']);
	                	$query->where('work_type', 'LIKE', $input['work_type'].'%');
	                }
	                if(!empty($input['subject'])) {
	                	//tieto 2 query su tu kvoli situaciam, aby nenaslo pre kucove slovo napr. "les" aj diela s klucovy slovom "pleso"
	                	$query->whereRaw('( subject LIKE "%'.$input['subject'].';%" OR subject LIKE "%'.$input['subject'].'" )');
	                }
	                if(!empty($input['gallery'])) {
	                	$query->where('gallery', 'LIKE', '%'.$input['gallery'].'%');
	                }
	                if(!empty($input['year-range'])) {
	                	$range = explode(',', $input['year-range']);
	                	// dd("where('date_earliest', '>', $range[0])->where('date_latest', '<', $range[1])");
	                	$query->where('date_earliest', '>', $range[0])->where('date_latest', '<', $range[1]);
	                }
	                if(!empty($input['free_download'])) {
	                	$query->where('free_download', '=', '1')->whereNotNull('iipimg_url');
	                }

	                return $query;
	            })
	           ->orderBy('created_at', 'DESC')->paginate(18);
		}

		$queries = DB::getQueryLog();
		$last_query = end($queries);
		// dd($last_query);

		return View::make('katalog', array(
			'items'=>$items, 
			'authors'=>$authors, 
			'work_types'=>$work_types, 
			'tags'=>$tags, 
			'galleries'=>$galleries, 
			'search'=>$search, 
			'input'=>$input, 
			));
	}

	public function getSuggestions()
	{
	 	$q = (Input::has('search')) ? Input::get('search') : 'null';

		$client = new Elasticsearch\Client();

		$result = $client->search([
	        	'index' => Config::get('app.elasticsearch.index'),
	        	'type' => Item::ES_TYPE,
	        	'body'  => array(
	                'query' => array(
	                    'multi_match' => array(
	                        'query'  	=> $q,
	                        'type' 		=> 'cross_fields',
							'fuzziness' =>  1.1,
							// 'slop'		=>  2,
        	                'fields' 	=> array("author", "title"),
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
			$data['count']++;
			$data['results'][] = array_merge(
				['id' => $hit['_id']],
				$hit['_source']
			) ;
		}

	    return Response::json($data);	
	}


}