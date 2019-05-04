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
use App\Item;
use App\Slide;
use App\Order;
use App\Color;
use App\Authority;

Route::group(['domain' => 'media.webumenia.{tld}'], function () {
    Route::get('/', function ($tld) {
        return "webumenia media server";
    });
    Route::get('{id}', function ($tld, $id) {
        $item = Item::find($id);
        if ($item) {
            return config('app.url') . $item->getImagePath();
        }
    });
});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localizeElastic' ]
],
function()
{
    Route::get('leto', function () {

        return redirect('kolekcia/25');
    });

    Route::get('/', function () {

        $params = array();
        $params["query"]["filtered"]["filter"]["bool"]["must"][]["term"]["type"] = 'author';
        $authors = Authority::search($params);

        return view('intro', [
            'authors' => $authors,
        ]);
    });

    Route::get('klucove-slova', function () {

        $tags = \App\Authority::existingTags();

        return view('tags', [
            'tags' => $tags,
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

        $items = Item::with('images')->find(Session::get('cart', array()));

        return view('objednavka', array('items' => $items));
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

    Route::get('dakujeme', function () {

        return view('dakujeme');
    });

    Route::get('dielo/{id}/zoom', 'ZoomController@getIndex')->name('item.zoom');

    Route::get('ukaz_skicare', 'SkicareController@index');
    Route::get('skicare', 'SkicareController@getList');
    Route::get('dielo/{id}/skicar', 'SkicareController@getZoom');

    Route::get('dielo/{id}/objednat', function ($id) {

        $item = Item::find($id);

        if (empty($item) || !$item->isForReproduction()) {
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

    Route::get('dielo/{id}/stiahnut', ['middleware' => 'throttle:5,1', function ($id) {

        $item = Item::find($id);

        if (empty($item) || !$item->publicDownload()) {
        	App::abort(404);
        }
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

        // $more_items = Item::moreLikeThis(['author','title.stemmed','description.stemmed', 'tag', 'place'],[$item->id])->limit(20);
        $more_items = $item->moreLikeThis(30);

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
            $ids = $item->similarByColor(100)->pluck('id');
            $similar_by_color = Item::whereIn('id', $ids)->get();
            $similar_by_color = $similar_by_color->filter(function (Item $i) use ($item) {
                return (bool)$i->color_descriptor && $item->id != $i->id;
            });
            $similar_by_color = $similar_by_color->sort(function($a, $b) use ($ids) {
                return $ids->search($a->id) - $ids->search($b->id);
            });

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

    Route::get('dielo/nahlad/{id}/{width}/{height?}', 'ImageController@resize')->where('width', '[0-9]+')->where('height', '[0-9]+')->name('dielo.nahlad');

    Route::controller('patternlib', 'PatternlibController');

    Route::controller('katalog', 'CatalogController');
    // Route::match(array('GET', 'POST'), 'katalog', 'CatalogController@index');
    // Route::match(array('GET', 'POST'), 'katalog/suggestions', 'CatalogController@getSuggestions');

    Route::match(array('GET', 'POST'), '{authorityType}', 'AuthorController@getIndex')->where([
        'authorityType' => 'umelci|teoretici',
    ]);

    Route::match(array('GET', 'POST'), 'umelci/suggestions', 'AuthorController@getSuggestions');
    Route::get('umelec/{id}', 'AuthorController@getDetail');
    Route::get('teoretik/{id}', 'AuthorController@getDetail');

    Route::match(array('GET', 'POST'), 'clanky', 'ClanokController@getIndex');
    Route::match(array('GET', 'POST'), 'clanky/suggestions', 'ClanokController@getSuggestions');
    Route::get('clanok/{slug}', 'ClanokController@getDetail');

    Route::match(array('GET', 'POST'), 'kolekcie', 'KolekciaController@getIndex');
    Route::match(array('GET', 'POST'), 'kolekcie/suggestions', 'KolekciaController@getSuggestions');
    Route::get('kolekcia/{slug}', 'KolekciaController@getDetail');

    Route::get('informacie', function () {
        $items = Item::random(20, ['gallery' => 'Slovenská národná galéria, SNG']);

        $galleries = [
            [
                'id'          => 'SNG',
                'lang_string' => 'informacie.info_gallery_SNG',
                'url'         => 'katalog?gallery=Slovenská národná galéria, SNG',
            ],
            [
                'id'          => 'OGD',
                'lang_string' => 'informacie.info_gallery_OGD',
                'url'         => 'katalog?gallery=Oravská galéria, OGD',
            ],
            [
                'id'          => 'GNZ',
                'lang_string' => 'informacie.info_gallery_GNZ',
                'url'         => 'katalog?gallery=Galéria umenia Ernesta Zmetáka, GNZ',
            ],
            [
                'id'          => 'GPB',
                'lang_string' => 'informacie.info_gallery_GPB',
                'url'         => 'katalog?gallery=Liptovská galéria Petra Michala Bohúňa, GPB',
            ],
            [
                'id'          => 'GMB',
                'lang_string' => 'informacie.info_gallery_GMB',
                'url'         => 'katalog?gallery=Galéria mesta Bratislavy, GMB',
            ],
            [
                'id'          => 'GBT',
                'lang_string' => 'informacie.info_gallery_GBT',
                'url'         => 'katalog?gallery=Galéria+Miloša+Alexandra+Bazovského, GBT',
            ],
            [
                'id'          => 'NGN',
                'lang_string' => 'informacie.info_gallery_NGN',
                'url'         => 'katalog?gallery=Nitrianska+galéria, NGN',
            ],
            [
                'id'          => 'SGB',
                'lang_string' => 'informacie.info_gallery_SGB',
                'url'         => 'katalog?gallery=Stredoslovenská galéria, SGB',
            ],
            [
                'id'          => 'GUS',
                'lang_string' => 'informacie.info_gallery_GUS',
                'url'         => 'katalog?gallery=Galéria umelcov Spiša, GUS',
            ],
            [
                'id'          => 'VSG',
                'lang_string' => 'informacie.info_gallery_VSG',
                'url'         => 'katalog?gallery=Východoslovenská+galéria%2C+VSG',
            ],
            [
                'id'          => 'MG',
                'lang_string' => 'informacie.info_gallery_MG',
                'url'         => 'katalog?gallery=Moravská galerie, MG',
            ],
        ];

        return view('informacie', [
            'items' => $items,
            'galleries' => $galleries,
        ]);
    });

    Route::get('reprodukcie', function () {
        $items_print   = Item::random(20, ['gallery' => 'Slovenská národná galéria, SNG']);
        $items_digital = Item::random(20, ['is_free' => true]);
        return view('reprodukcie', ['items_print' => $items_print, 'items_digital' => $items_digital]);
    });

    Route::get('o-projekte', function () {
        return view('khb.o-projekte');
    });

    Route::get('skupiny', function () {
        return view('khb.skupiny');
    });

    Route::get('vystavne-priestory', function () {
        return view('khb.vystavne-priestory');
    });

    Route::post('newsletter/signup', function (\Illuminate\Http\Request $request) {
        $email = $request->input('email');
        $result = Newsletter::subscribe($email);

        $status = trans('newsletter.signup_success_message');
        $response = back();

        if ($result === false) {
            $status = trans('newsletter.signup_failed_message');
            $lastError = Newsletter::getLastError();
            $status = $lastError ? sprintf('%s (%s)', $status, $lastError) : $status;
            $response = $response->with('alert-class', 'alert-danger');
        }

        $response = $response->with('status', $status);

        return $response;
    })->name('newsletter.signup');
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
});

Route::group(['middleware' => ['auth', 'role:admin|editor']], function () {

    Route::get('collection/{collection_id}/detach/{item_id}', 'CollectionController@detach');
    Route::post('collection/fill', 'CollectionController@fill');
    Route::post('collection/sort', 'CollectionController@sort');
    Route::resource('collection', 'CollectionController');
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
    Route::get('authority/search', 'AuthorityController@search');
    Route::post('authority/media', 'AuthorityController@storeMedia')->name('authority.storeMedia');
    Route::resource('authority', 'AuthorityController');
    Route::resource('sketchbook', 'SketchbookController');
    Route::resource('slide', 'SlideController');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

