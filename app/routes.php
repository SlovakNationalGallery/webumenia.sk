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
	return '<html><body><a href="/solr">SOLR test</a><br> <a href="/oai/test">OAI test</a></body></html>';
});



Route::get('/spice_harvester', 'SpiceHarvesterController@index');


Route::get('/solr', function()
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


	return View::make('solr', array('data' => $result));
	// return $this->layout->nest('content', 'test',array('data' => $result));
});

Route::post('/solr', function()
{
	// global $layout;

	$name = Input::get('name');
	// return $name;

	// $url = "http://127.0.0.1:8080/solr/select/?q=withImages%3Atrue+OR+au%3A+\"$name*\"+OR+ti%3A\"$name*\"&start=0&rows=4&wt=json";
	$url = "http://127.0.0.1:8080/solr/select/?q=%28au%3A\"$name*\"+OR+ti%3A\"$name*\"%29+AND+withImages%3Atrue&start=0&rows=16&wt=json";

	$content = file_get_contents($url);
	if($content) {
		$result = json_decode($content);
		// var_dump($result); die();
	}

	// return $layout->nest('content', 'test',array('data' => $result));
	return View::make('solr', array('data' => $result));
});


Route::get('/oai/test', function()
{

	// return 'OAI-PMH test';

	$client = new \Phpoaipmh\Client('http://www.webumenia.sk/oai-pmh');
    $myEndpoint = new \Phpoaipmh\Endpoint($client);

    $result = $myEndpoint->identify();
    return var_dump($result);
});

Route::get('/oai', function()
{

	// return 'OAI-PMH test';

	$client = new \Phpoaipmh\Client('http://www.webumenia.sk/oai-pmh');
    $myEndpoint = new \Phpoaipmh\Endpoint($client);

    $results = $myEndpoint->listMetadataFormats();
    foreach($results as $item) {
        // var_dump($item);
    }

    $rec = $myEndpoint->getRecord('SVK:SNG.G_3671', 'oai_dc');
    $myRec = $rec->GetRecord;
    dd($myRec->record->metadata->children('oai_dc', 1)->dc->children('dc', 1));
    // dd($myRec->metadata->children('oai_dc', 1));
    // $rNode->metadata->children('oai_dc', 1)->dc->children('dc', 1));


    // $recs = $myEndpoint->listIdentifiers('oai_dc', '2014-03-17', NULL, 'Europeana SNG');
    $recs = $myEndpoint->listRecords('ese', '2014-03-17', NULL, 'Europeana SNG');
    // dd($recs);
    $rec = $recs->nextItem(); dd($rec); 

    //nextItem will continue retrieving items even across HTTP requests.
    //You can keep running this loop through the *entire* collection you
    //are harvesting.  It returns a SimpleXMLElement object, or false when
    //there are no more records.
    while($rec = $recs->nextItem()) {
        var_dump($rec->metadata);
    }

    die();

	return View::make('webumenia', array('data' => $result));
});



Route::get('login', 'AuthController@getLogin');
Route::post('login', 'AuthController@postLogin');


Route::group(array('before' => 'auth'), function(){

	Route::get('admin', 'AdminController@index');
	Route::get('logout', 'AuthController@logout');
	Route::get('harvests/launch/{id}', 'SpiceHarvesterController@launch');
	Route::resource('harvests', 'SpiceHarvesterController');
	// Route::resource('posts', "PostController");

});
