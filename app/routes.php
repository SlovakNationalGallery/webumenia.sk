<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function() 
{

	//SOLR
	// $url = "http://127.0.0.1:8080/solr/select/?q=*%3A*&start=0&rows=10&wt=json";
	/*

	/select params={sort=relevation+desc&start=0&q=wt:"socharstvo*"&wt=javabin&fq=st:"Dielo+katalogizované"&fq=withImages:true&rows=14&version=2.2} 
searchable=true
 190501    galeryFullName=<null>
 190502:   withImages=true
 190503    id=SVK:SNG.G_114


*/
	// $url = "http://127.0.0.1:8080/solr/select/?q=withImages%3Atrue&start=0&rows=10&wt=json";
	$url = "http://127.0.0.1:8080/solr/select/?q=withImages%3Atrue&start=0&rows=16&wt=json";
	 
	$content = file_get_contents($url);
	if($content) {
		$result = json_decode($content);
		// var_dump($result); die();
	}


	return View::make('webumenia', array('data' => $result));
	// return $this->layout->nest('content', 'test',array('data' => $result));
});

Route::post('/', function() 
{
	// global $layout;

	$name = Input::get('name');
	// return $name;

	// $url = "http://127.0.0.1:8080/solr/select/?q=withImages%3Atrue+OR+au%3A+\"$name*\"+OR+ti%3A\"$name*\"&start=0&rows=4&wt=json";
	$url = "http://127.0.0.1:8080/solr/select/?q=%28au%3A\"$name*\"+OR+ti%3A\"$name*\"%29+AND+withImages%3Atrue&start=0&rows=4&wt=json";
	 
	$content = file_get_contents($url);
	if($content) {
		$result = json_decode($content);
		// var_dump($result); die();
	}

	// return $layout->nest('content', 'test',array('data' => $result));
	return View::make('webumenia', array('data' => $result));
});

Route::get('/test', function() 
{
	return 'test!';
});


Route::get('/oai', function() 
{

	// return 'OAI-PMH test';

	$client = new \Phpoaipmh\Client('http://www.webumenia.sk/oai-pmh');
    $myEndpoint = new \Phpoaipmh\Endpoint($client);

    $result = $myEndpoint->identify();
    return var_dump($result);


	$content = file_get_contents($url);
	if($content) {
		$result = json_decode($content);
		// var_dump($result); die();
	}


	return View::make('webumenia', array('data' => $result));
});
