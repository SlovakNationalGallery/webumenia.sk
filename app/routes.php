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
	return Redirect::to('katalog');
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

	if (!in_array($item->id, Session::get('cart', array()))) Session::push('cart', $item->id);
	
	Session::flash('message', "Dielo <b>" . implode(', ', $item->authors) . " – $item->title</b> (".$item->getDatingFormated().") bolo pridané do košíka.");
	return Redirect::to($item->getDetailUrl());

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
});

Route::get('creative-commons', function()
{
	$items = Item::where('free_download', '=', '1')->orderBy('created_at', 'DESC')->paginate(18);

	return View::make('katalog', array(
		'items'=>$items, 
		'cc'=>true, 
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
	Route::get('item/reindex', 'ItemController@reindex');
	Route::resource('item', 'ItemController');
	Route::post('collection/fill', 'CollectionController@fill');
	Route::resource('collection', 'CollectionController');

});

App::missing(function($exception)
{
    return Response::view('errors.missing', array('transparent_menu'=>true), 404);
});
