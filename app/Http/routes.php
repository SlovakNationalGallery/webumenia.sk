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

use App\Article;
use App\Collection;
use App\ItemImage;
use App\Item;
use App\Slide;
use App\Order;
use App\Color;
use Illuminate\Contracts\Encryption\DecryptException;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localizeElastic' ]
],
function()
{
    Route::get('/', function () {

        $slides = Slide::published()->orderBy('id', 'desc')->get();

        $collection = Collection::find(1);

        if (empty($collection)) {
            $items = Item::inRandomOrder()->take(20)->get();
        } else {
            $items=$collection->items()->inRandomOrder()->get();
        }

        return view('intro', [
            'slides' => $slides,
            'items'=>$items,
        ]);
    });

    Route::get('slideClicked', function () {
        $slide = Slide::find(Input::get('id'));
        if ($slide) {
            $slide->click_count += 1;
            $slide->save();

            return Response::json(['status' => 'success']);
        }
    });

    Route::get('objednavka', function () {

        $items = Item::find(Session::get('cart', array()));

        return view('objednavka', array('items' => $items));
    });

    Route::get('stiahnutie', function () {

        $items = Item::find(Session::get('cart', array()));

        return view('stiahnutie', array('items' => $items));
    });

    Route::post('objednavka', function () {

        $input = Input::all();

        $rules = Order::$rules;
        $v = Validator::make($input, $rules);
        $v->sometimes('purpose', 'required|max:1500', function ($input) {

            return $input->format == 'digitálna reprodukcia';
        });
        $v->sometimes('delivery_point', 'required', function ($input) {

            return $input->format != 'digitálna reprodukcia';
        });

        if ($v->passes()) {
            $order = new Order();
            $order->name = Input::get('name');
            $order->address = Input::get('address');
            $order->email = Input::get('email');
            $order->phone = Input::get('phone');
            $order->format = Input::get('format');
            $order->purpose_kind = Input::get('purpose_kind');
            $order->purpose = Input::get('purpose');
            $order->delivery_point = Input::get('delivery_point', null);
            $order->note = Input::get('note');
            $order->save();

            $item_ids = explode(', ', Input::get('pids'));

            foreach ($item_ids as $item_id) {
                $order->items()->attach($item_id);
            }

            //poslat objednavku do Jiry
            $client = new GuzzleHttp\Client();
            $res = $client->post('https://jira.sng.sk/rest/cedvu/latest/order/create', [
                'auth' => [Config::get('app.jira_auth.user'), Config::get('app.jira_auth.pass')],
                'form_params' => [
                    'pids' => Input::get('pids'),
                    'organization' => $order->name,
                    'contactPerson' => $order->name,
                    'email' => $order->email,
                    'kindOfPurpose' => $order->purpose_kind,
                    'purpose' => $order->purpose."\n".$order->format."\n".$order->delivery_point."\n".$order->note,
                    'medium' => 'Iné',
                    'address' => $order->address,
                    'phone' => $order->phone,
                    'ico' => '',
                    'dic' => '',
                    'numOfCopies' => '1',
                ],
            ]);
            if ($res->getStatusCode() == 200) {
                Session::forget('cart');

                return redirect('dakujeme');
            } else {
                Session::flash('message', 'Nastal problém pri uložení vašej objednávky. Prosím kontaktujte lab@sng.sk. ');

                return Redirect::back()->withInput();
            }
        }

        return Redirect::back()->withInput()->withErrors($v);

    })->name('objednavka.post');



    Route::post('download', function (\Illuminate\Http\Request $request) {

        $input = array_merge($request->all(), ['ip' => $request->ip()]);
        // dd($input);

        $rules = \App\Download::$rules;
        $v = Validator::make($input, $rules);

        if ($v->passes()) {
            $download = \App\Download::create($input);

            $item_ids = explode(', ', $request->input('pids'));

            foreach ($item_ids as $item_id) {
                $download->items()->attach($item_id);
            }

            Session::forget('cart');

            $download_tracker = Crypt::encrypt($download->id);

            return redirect('dakujeme_download')->with('download_tracker', $download_tracker);


            // increment download counter
            // download the image

            /*

            $item = Item::find($request->input('item_id'));

            if (empty($item) || !$item->isFreeDownload()) {
                App::abort(404);
            }
            $item->timestamps = false;
            $item->download_count += 1;
            $item->save();
            return $item->download();
            */
        }

        return Redirect::back()->withInput()->withErrors($v);


    })->name('objednavka.download');

    Route::get('dakujeme_download', function (\Illuminate\Http\Request $request) {
        $download_tracker = $request->session()->get('download_tracker');
        try {
            $download_id = Crypt::decrypt($download_tracker);
        } catch (DecryptException $e) {
            // die('The payload is invalid');
            return redirect('/');
        }
        $download = \App\Download::find($download_id);

        $download_urls = [];

        foreach ($download->items as $item) {
            $download_urls[] = URL::to('dielo/' . $item->id . '/stiahnut/' . $download_tracker);
        }

        return view('dakujeme_download', ['download_urls' => $download_urls]);
    });

    Route::get('dakujeme', function () {

        return view('dakujeme');
    });

    Route::get('dielo/{id}/zoom', function ($id) {

        $item = Item::find($id);


        if (empty($item->has_iip)) {
            App::abort(404);
        }

        $images = $item->getZoomableImages();
        $index =  0;
        if ($images->count() <= 1 && !empty($item->related_work)) {
            $related_items = Item::related($item)->with('images')->get();

            $images = collect();
            foreach ($related_items as $related_item) {
                if ($image = $related_item->getZoomableImages()->first()) {
                    $images->push($image);
                }
            }

            $index = $images->search(function (ItemImage $image) use ($item) {
                return $image->item->id == $item->id;
            });
        }

        return view('zoom', array('item' => $item, 'images' => $images, 'index' => $index));
    })->name('item.zoom');

    Route::get('ukaz_skicare', 'SkicareController@index');
    Route::get('skicare', 'SkicareController@getList');
    Route::get('dielo/{id}/skicar', 'SkicareController@getZoom');

    Route::get('dielo/{id}/objednat', function ($id) {

        $item = Item::find($id);

        if (empty($item) || !$item->isFreeDownload()) {
            App::abort(404);
        }

        if (!in_array($item->id, Session::get('cart', array()))) {
            Session::push('cart', $item->id);
        }

        Session::flash( 'message', trans('objednavka.message_add_order', ['artwork_description' => '<b>'.$item->getTitleWithAuthors().'</b> ('.$item->getDatingFormated().')']) );

        return redirect($item->getUrl());

    });

    Route::get('dielo/{id}/odstranit', function ($id) {

        $item = Item::find($id);

        if (empty($item)) {
            App::abort(404);
        }
        Session::put('cart', array_diff(Session::get('cart'), [$item->id]));
        Session::flash('message', trans('objednavka.message_remove_order', ['artwork_description' => '<b>'.$item->getTitleWithAuthors().'</b> ('.$item->getDatingFormated().')']) );

        return Redirect::back();

    });

    Route::get('dielo/{id}/stiahnut/{download_tracker}', ['middleware' => 'throttle:5,1', function ($id, $download_tracker) {

        $download_id = Crypt::decrypt($download_tracker);
        $download = \App\Download::find($download_id);
        $item = Item::find($id);
        $now = Carbon::now();

        if (
            empty($download) ||
            empty($item) ||
            !$download->hasItem($item) ||
            $download->created_at->diffInMinutes($now) > 30 ||
            !$item->isFreeDownload()
        ) {
        	App::abort(404);
        }

        $item->timestamps = false;
        $item->download_count += 1;
        $item->save();
        return $item->download();
        exit;

        // return Response::download($pathToFile);
    }]);

    Route::get('dielo/{id}', function ($id) {

        $item = Item::find($id);
        if (empty($item)) {
            App::abort(404);
        }
        $item->timestamps = false;
        $item->view_count += 1;
        $item->save();
        $previous = $next = false;

        $more_items = $item->getSimilarArtworks(30);

        if (Input::has('collection')) {
            $collection = Collection::find((int) Input::get('collection'));
            if (!empty($collection)) {
                $items = $collection->items->lists('id')->all();
                $previousId = getPrevVal($items, $id);
                if ($previousId) {
                    $previous = Item::find($previousId)->getUrl(['collection' => $collection->id]);
                }
                $nextId = getNextVal($items, $id);
                if ($nextId) {
                    $next = Item::find($nextId)->getUrl(['collection' => $collection->id]);
                }
            }
        }

        $similar_by_color = [];
        $colors_used = [];

        if ($item->color_descriptor) {
            $similar_by_color = $item->similarByColor(100);
            $colors_used = $item->getColorsUsed(Color::TYPE_HEX);

            uasort($colors_used, function ($a, $b) {
                if ($a['amount'] == $b['amount']) {
                    return 0;
                }

                return $a['amount'] < $b['amount'] ? 1 : -1;
            });

            $amount_sum = array_sum(array_column($colors_used, 'amount'));
            foreach ($colors_used as $hex => $color_used) {
                $colors_used[$hex]['amount'] = sprintf("%.3f%%", $colors_used[$hex]['amount'] * 100 / $amount_sum, 3);
                $colors_used[$hex]['hex'] = $colors_used[$hex]['color']->getValue();
            }
        }

        return view('dielo', compact(
            'item',
            'more_items',
            'similar_by_color',
            'colors_used',
            'previous',
            'next'
        ));
    });

    Route::get('dielo/nahlad/{id}/{width}', function ($id, $width) {

        if (
            ($width <= 800) &&
            Item::where('id', '=', $id)->exists()
        ) {
            // disable resizing when requesting 800px width
            $width = ($width == 800) ? false : $width;
            $resize_method = 'widen';
            $imagePath = public_path() . Item::getImagePathForId($id, false, $width, $resize_method);

            return response()->file($imagePath);
        }

        return App::abort(404);

    })->where('width', '[0-9]+')->name('dielo.nahlad');

    Route::controller('patternlib', 'PatternlibController');

    Route::controller('katalog', 'CatalogController');
    // Route::match(array('GET', 'POST'), 'katalog', 'CatalogController@index');
    // Route::match(array('GET', 'POST'), 'katalog/suggestions', 'CatalogController@getSuggestions');

    Route::match(array('GET', 'POST'), 'autori', 'AuthorController@getIndex');
    Route::match(array('GET', 'POST'), 'autori/suggestions', 'AuthorController@getSuggestions');
    Route::get('autor/{id}', 'AuthorController@getDetail');

    Route::match(array('GET', 'POST'), 'clanky', 'ClanokController@getIndex');
    Route::match(array('GET', 'POST'), 'clanky/suggestions', 'ClanokController@getSuggestions');
    Route::get('clanok/{slug}', 'ClanokController@getDetail');

    Route::match(array('GET', 'POST'), 'kolekcie', 'KolekciaController@getIndex');
    Route::match(array('GET', 'POST'), 'kolekcie/suggestions', 'KolekciaController@getSuggestions');
    Route::get('kolekcia/{slug}', 'KolekciaController@getDetail');

    Route::get('informacie', function () {

        $ids = [5, 19, 2, 4, 20, 18, 21];
        $collections = Collection::whereIn('id', $ids)->get()->sort(function ($a, $b) use ($ids) {
            return array_search($a->id, $ids) - array_search($b->id, $ids);
        });

        return view('informacie', [
            'collections' => $collections
        ]);
    });

    Route::get('500', function () {
        abort(500);
    });

});

Route::group(array('middleware' => 'guest'), function () {
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
});

Route::group(['middleware' => ['auth', 'role:admin|editor|import']], function () {
    Route::get('admin', 'AdminController@index');
    Route::get('logout', 'AuthController@logout');
    Route::get('imports/launch/{id}', 'ImportController@launch');
    Route::resource('imports', 'ImportController');
    Route::get('item/search', 'ItemController@search');
    Route::resource('item', 'ItemController');
    Route::post('item/destroySelected', 'ItemController@destroySelected');
    Route::get('collection/{collection_id}/detach/{item_id}', 'CollectionController@detach');
    Route::post('collection/fill', 'CollectionController@fill');
    Route::post('collection/sort', 'CollectionController@sort');
    Route::resource('collection', 'CollectionController');
    Route::resource('slide', 'SlideController');
    Route::get('download', 'DownloadController@index');
    Route::get('download/export', 'DownloadController@export');
    Route::get('download/{id}', 'DownloadController@show');
});

Route::group(['middleware' => ['auth', 'role:admin|editor']], function () {
    Route::resource('user', 'UserController');
    Route::match(['get', 'post'], 'uploader', 'FileuploaderController@upload');
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::resource('article', 'ArticleController');
    Route::get('harvests/launch/{id}', 'SpiceHarvesterController@launch');
    Route::get('harvests/orphaned/{id}', 'SpiceHarvesterController@orphaned');
    Route::get('harvests/{record_id}/refreshRecord/', 'SpiceHarvesterController@refreshRecord');
    Route::resource('harvests', 'SpiceHarvesterController');
    Route::get('item/backup', 'ItemController@backup');
    Route::get('item/geodata', 'ItemController@geodata');
    Route::post('item/refreshSelected', 'ItemController@refreshSelected');
    Route::get('item/reindex', 'ItemController@reindex');
    Route::get('authority/destroyLink/{link_id}', 'AuthorityController@destroyLink');
    Route::get('authority/reindex', 'AuthorityController@reindex');
    Route::post('authority/destroySelected', 'AuthorityController@destroySelected');
    Route::resource('authority', 'AuthorityController');
    Route::resource('sketchbook', 'SketchbookController');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

