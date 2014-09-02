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
	define('LAUNCH_DATE', '2014-07-02 10:00');

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

Route::get('objednavka', function()
{
	$items = Item::find(Session::get('cart',array()));
	return View::make('objednavka', array('items'=>$items));
});

Route::post('objednavka', function()
{

	$input = Input::all();

	$rules = Order::$rules;
	$v = Validator::make($input, $rules);

	if ($v->passes()) {
		
		$order = new Order;
		$order->name = Input::get('name');
		$order->address = Input::get('address');
		$order->email = Input::get('email');
		$order->phone = Input::get('phone');
		$order->format = Input::get('format');
		$order->note = Input::get('note');
		$order->save();

		$item_ids = explode(', ', Input::get('pids'));

		foreach ($item_ids as $item_id) {
			$order->items()->attach($item_id);
		}

		//poslat objednavku do Jiry
		$client = new GuzzleHttp\Client();
		$res = $client->post('http://jira.sng.sk/rest/cedvu/latest/order/create', [
		    'auth' =>  [Config::get('app.jira_auth.user'), Config::get('app.jira_auth.pass')],
			'body' => [
				'pids' => Input::get('pids'),
				'organization' => $order->name,
				'contactPerson' => $order->name,
				'email' => $order->email,
				'kindOfPurpose' => 'Súkromný',
				'purpose' => $order->format . "\n" . $order->note,
				'medium' => 'Iné',
				'address' => $order->address,
				'phone' => $order->phone,
				'ico' => '',
				'dic' => '',
				'numOfCopies' => '1'
		    ]    
		]);
		if ($res->getStatusCode()==200) {
			Session::forget('cart');			
			return Redirect::to('dakujeme');
		} else {
			Session::flash('message', "Nastal problém pri uložení vašej objednávky. Prosím kontaktujte lab@sng.sk. ");
			return Redirect::back()->withInput();
		}
	}

	return Redirect::back()->withInput()->withErrors($v);

});

Route::get('dakujeme', function()
{
	return View::make('dakujeme');
});

Route::get('dielo/{id}/zoom', function($id)
{
	$item = Item::find($id);

	if (empty($item->iipimg_url)) {
		App::abort(404);
	}
	return View::make('zoom', array('item'=>$item));
});

Route::get('dielo/{id}/objednat', function($id)
{
	$item = Item::find($id);

	if (empty($item) || !$item->isForReproduction()) {
		App::abort(404);
	}

	Session::push('cart', $item->id);
	Session::flash('message', "Dielo <b>" . implode(', ', $item->authors) . " – $item->title</b> (".$item->getDatingFormated().") bolo pridané do košíka.");
	return Redirect::back();

});

Route::get('dielo/{id}/odstranit', function($id)
{
	$item = Item::find($id);

	if (empty($item)) {
		App::abort(404);
	}

	Session::put('cart', array_diff(Session::get('cart'), [$item->id]));
	Session::flash('message', "Dielo <b>" . implode(', ', $item->authors) . " – $item->title</b> (".$item->getDatingFormated().") bolo odstránené z košíka.");
	return Redirect::back();

});

Route::get('dielo/{id}/stiahnut', function($id)
{
	$item = Item::find($id);

	if (empty($item) || !$item->isFreeDownload()) {
		App::abort(404);
	}

	$item->download();

	// return Response::download($pathToFile);
});

Route::get('dielo/{id}', function($id)
{
	$item = Item::find($id);
	if (empty($item)) {
		App::abort(404);
	}
	$item->view_count += 1; 
	$item->save();
	$collection = $item->collections->first();
	return View::make('dielo', array('item'=>$item, 'collection' => $collection ));
});

Route::get('sekcia/{id}', function($id)
{
	$collection = Collection::find($id);
	return View::make('sekcia', array('collection'=>$collection));
});

// Route::get('katalog', function()
Route::match(array('GET', 'POST'), 'katalog', function()	
{
	$search = Input::get('search', null);
	$input = Input::all();
	// dd($input);

	$authors = Item::listValues('author');
	$work_types = Item::listValues('work_type', ',', true);
	$tags = Item::listValues('subject');
	$galleries = Item::listValues('gallery');

	/*
	if (Input::has('search')) {
		$search = Input::get('search', null);
		// $items = Item::where('title', 'LIKE', '%'.$search.'%')->orWhere('author', 'LIKE', '%'.$search.'%')->orWhere('subject', 'LIKE', '%'.$search.'%')->orWhere('id', 'LIKE', '%'.$search.'%')->orderBy('created_at', 'DESC')->paginate(12);
	} else {
		$items = Item::orderBy('created_at', 'DESC')->paginate(12);
	}
	*/
	// dd($input);

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
                	$query->where('subject', 'LIKE', '%'.$input['subject'].';%');
                	$query->orWhere('subject', 'LIKE', '%'.$input['subject'].'');
                }
                if(!empty($input['gallery'])) {
                	$query->where('gallery', 'LIKE', '%'.$input['gallery'].'%');
                }
                if(!empty($input['year-range'])) {
                	$range = explode(',', $input['year-range']);
                	// dd("where('date_earliest', '>', $range[0])->where('date_latest', '<', $range[1])");
                	$query->where('date_earliest', '>', $range[0])->where('date_latest', '<', $range[1]);
                }

                return $query;
            })
           ->orderBy('created_at', 'DESC')->paginate(12);


	return View::make('katalog', array(
		'items'=>$items, 
		'authors'=>$authors, 
		'work_types'=>$work_types, 
		'tags'=>$tags, 
		'galleries'=>$galleries, 
		'search'=>$search, 
		'input'=>$input, 
		));
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
	Route::get('harvests/orphaned/{id}', 'SpiceHarvesterController@orphaned');
	Route::resource('harvests', 'SpiceHarvesterController');
	Route::get('item/backup', 'ItemController@backup');
	Route::get('item/geodata', 'ItemController@geodata');
	Route::post('item/destroySelected', 'ItemController@destroySelected');
	Route::get('item/search', 'ItemController@search');
	Route::resource('item', 'ItemController');
	Route::post('collection/fill', 'CollectionController@fill');
	Route::resource('collection', 'CollectionController');

});

App::missing(function($exception)
{
    return Response::view('errors.missing', array('transparent_menu'=>true), 404);
});
