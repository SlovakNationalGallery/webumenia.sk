<?php

class ClanokController extends \BaseController {

	public function getIndex()
	{
		$articles = Article::published()->orderBy('published_date', 'desc');
		if (Input::has('author')) {
			$articles = $articles->where('author', 'LIKE', Input::get('author'));
		}
		$articles = $articles->get();
		return View::make('clanky', array('articles'=>$articles));
	}

	public function getSuggestions()
	{
	 	$q = (Input::has('search')) ? str_to_alphanumeric(Input::get('search')) : 'null';

		$result = Elastic::search([
	        	'type' => Article::ES_TYPE,
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
				'image' => Article::getImagePathForId($hit['_id'], $hit['_source']['has_image'], $hit['_source']['sex'],  false, 70)
			);
			$data['results'][] = array_merge($params) ;
		}

	    return Response::json($data);	
	}

	public function getDetail($slug)
	{
		// dd($slug);
		$article = Article::where('slug', '=', $slug)->firstOrFail();
		if (empty($article)) {
			App::abort(404);
		}
		$article->view_count += 1; 
		$article->save();

		return View::make('clanok', array('article'=>$article));

	}


}