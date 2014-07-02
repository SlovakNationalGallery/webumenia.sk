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
	define('LAUNCH_DATE', '2014-07-03 08:00');

	$launch_date = strtotime(LAUNCH_DATE);

	$collections = Collection::orderBy('order', 'ASC')->with('items')->get();

	foreach ($collections as $i => $collection) {
		$path = '/images/sekcie2/';
		$filename = $collection->id . '.jpeg';
		if (!file_exists(public_path() . '/images/sekcie/' . $filename)) {
			Image::make(public_path() . $path . $filename)->fit(500, 300)->save('images/sekcie/' . $filename);		
		}
	}

	$items = Item::where('lat', '>', '0')->get();

	$template = 'intro';
	if ($launch_date > time() && !Auth::check()) {
		$template = 'comming';
	}

	return View::make($template, array('collections'=>$collections, 'items'=>$items));
});

Route::get('dielo/{id}/zoom', function($id)
{
	$item = Item::find($id);

	if (empty($item->iipimg_url)) {
		App::abort(404);
	}

	return View::make('zoom', array('item'=>$item));
});

Route::get('dielo/{id}', function($id)
{
	$item = Item::find($id);
	if (empty($item)) {
		App::abort(404);
	}
	return View::make('dielo', array('item'=>$item));
});

Route::get('sekcia/{id}', function($id)
{
	$collection = Collection::find($id);
	return View::make('sekcia', array('collection'=>$collection));
});

Route::get('informacie', function()
{
	return View::make('informacie');
});

Route::group(array('before' => 'guest'), function(){
	Route::get('login', 'AuthController@getLogin');
	Route::post('login', 'AuthController@postLogin');
});

Route::group(array('before' => 'auth'), function(){

	Route::get('admin', 'AdminController@index');
	Route::get('logout', 'AuthController@logout');
	Route::get('harvests/launch/{id}', 'SpiceHarvesterController@launch');
	Route::resource('harvests', 'SpiceHarvesterController');
	Route::get('item/backup', 'ItemController@backup');
	Route::get('item/geodata', 'ItemController@geodata');
	Route::post('search', 'ItemController@search');
	Route::resource('item', 'ItemController');
	Route::post('collection/fill', 'CollectionController@fill');
	Route::resource('collection', 'CollectionController');

});

App::missing(function($exception)
{
    return Response::view('errors.missing', array(), 404);
});
