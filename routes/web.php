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
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\ItemFilter;
use App\Item;
use App\Notice;
use App\Order;
use App\Slide;

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
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]
],
function()
{
    Route::get('leto', function () {

        return redirect('kolekcia/25');
    });

    Route::get('/', function (
        AuthorityRepository $authorityRepository,
        ItemRepository $itemRepository
    ) {
        $choices = [
            [
                trans('intro.from_galleries_start'),
                route('frontend.info'),
                formatNum(15),
                trans('intro.from_galleries_end'),
            ],
            [
                trans('intro.from_authors_start'),
                route('frontend.author.index'),
                formatNum($authorityRepository->count()),
                trans('intro.from_authors_end'),
            ],
            [
                trans('intro.in_high_res_start'),
                route('frontend.catalog.index', ['has_iip' => true]),
                formatNum($itemRepository->count((new ItemFilter)->setHasIip(true))),
                trans('intro.in_high_res_end'),
            ],
            [
                trans('intro.are_free_start'),
                route('frontend.catalog.index', ['is_free' => true]),
                formatNum($itemRepository->count((new ItemFilter)->setIsFree(true))),
                trans('intro.are_free_end'),
            ],
        ];

        $choice = $choices[array_rand($choices)];
        $subtitle = vsprintf('%s <strong><a href="%s">%s</a></strong> %s', $choice);
        $slides = Slide::published()->orderBy('id', 'desc')->get();
        $articles = Article::with(['translations', 'category'])->promoted()->published()->orderBy('published_date', 'desc')->get();
        $itemCount = $itemRepository->count();

        return view('intro', [
            'subtitle' => $subtitle,
            'slides' => $slides,
            'articles' => $articles,
            'itemCount' => $itemCount,
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

        $allow_printed_reproductions = $items->reduce(function ($result, $item) {
            return $result & !$item->images->isEmpty();
        }, true);

        return view('objednavka', [
            'items' => $items,
            'allow_printed_reproductions' => $allow_printed_reproductions,
            'notice' => Notice::current(),
        ]);
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
            $order->frame = Input::get('frame');
            $order->purpose_kind = Input::get('purpose_kind');
            $order->purpose = Input::get('purpose');
            $order->delivery_point = Input::get('delivery_point', null);
            $order->note = Input::get('note');
            $order->save();

            $item_ids = explode(', ', Input::get('pids'));

            foreach ($item_ids as $item_id) {
                $order->items()->attach($item_id);
            }

            $type = (Input::get('format') == 'digitálna reprodukcia') ? 'digitálna' : 'tlačená';

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
                    'purpose' => $order->purpose."\n".$order->format."\n".$order->frame."\n".$order->delivery_point."\n".$order->note,
                    'medium' => 'Iné',
                    'address' => $order->address,
                    'phone' => $order->phone,
                    'ico' => '',
                    'dic' => '',
                    'numOfCopies' => '1',
                    'reproductionType' => $type,
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
            abort(404);
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
            abort(404);
        }
        Session::put('cart', array_diff(Session::get('cart', []), [$item->id]));
        Session::flash('message', trans('objednavka.message_remove_order', ['artwork_description' => '<b>'.$item->getTitleWithAuthors().'</b> ('.$item->getDatingFormated().')']) );

        return Redirect::back();

    });

    Route::get('dielo/{id}/stiahnut', ['middleware' => 'throttle:5,1', function ($id) {
        $item = Item::findOrFail($id);
        if ($item->images->isEmpty()) {
            abort(404);
        }

        return redirect()->route('image.download', ['id' => $item->images->first()->id]);
    }]);

    Route::get('dielo/{id}', function ($id, ItemRepository $itemRepository) {
        /** @var Item $item */
        $item = Item::find($id);
        if (empty($item)) {
            abort(404);
        }
        $item->timestamps = false;
        $item->view_count += 1;
        $item->save();
        $previous = $next = false;

        $similar_items = $itemRepository->getSimilar(12, $item)->getCollection();
        $related_items = (!empty($item->related_work)) ? Item::related($item)->get() : null;

        if (Input::has('collection')) {
            $collection = Collection::find((int) Input::get('collection'));
            if (!empty($collection)) {
                $items = $collection->items->pluck('id')->all();
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

        return view('dielo', compact(
            'item',
            'similar_items',
            'related_items',
            'previous',
            'next'
        ));
    });

    Route::get('dielo/{id}/colorrelated', function ($id, ItemRepository $itemRepository) {
        $item = Item::findOrFail($id);
        return view('dielo-colorrelated', [
            'similar_by_color' => $itemRepository->getSimilarByColor(12, $item)->getCollection(),
        ]);
    })->name('dielo.colorrelated');

    Route::get('dielo/nahlad/{id}/{width}/{height?}', 'ImageController@resize')->where('width', '[0-9]+')->where('height', '[0-9]+')->name('dielo.nahlad');
    Route::get('image/{id}/download', 'ImageController@download')->name('image.download');

    Route::get('patternlib', 'PatternlibController@getIndex')->name('frontend.patternlib.index');

    Route::get('katalog', 'CatalogController@getIndex')->name('frontend.catalog.index');
    Route::get('katalog/suggestions', 'CatalogController@getSuggestions')->name('frontend.catalog.suggestions');
    Route::get('katalog/random', 'CatalogController@getRandom')->name('frontend.catalog.random');

    Route::match(array('GET', 'POST'), 'autori', 'AuthorController@getIndex')->name('frontend.author.index');
    Route::match(array('GET', 'POST'), 'autori/suggestions', 'AuthorController@getSuggestions')->name('frontend.author.suggestions');
    Route::get('autor/{id}', 'AuthorController@getDetail')->name('frontend.author.detail');

    Route::match(array('GET', 'POST'), 'clanky', 'ClanokController@getIndex');
    Route::match(array('GET', 'POST'), 'clanky/suggestions', 'ClanokController@getSuggestions');
    Route::get('clanok/{slug}', 'ClanokController@getDetail');

    Route::match(array('GET', 'POST'), 'kolekcie', 'KolekciaController@getIndex')->name('frontend.collection.index');
    Route::match(array('GET', 'POST'), 'kolekcie/suggestions', 'KolekciaController@getSuggestions')->name('frontend.collection.suggestions');
    Route::get('kolekcia/{slug}', 'KolekciaController@getDetail')->name('frontend.collection.detail');

    Route::get('informacie', function (ItemRepository $itemRepository) {
        $filter = (new ItemFilter)->setGallery('Slovenská národná galéria, SNG');
        $items = $itemRepository->getRandom(20, $filter)->getCollection();

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
                'id'          => 'TGP',
                'lang_string' => 'informacie.info_gallery_TGP',
                'url'         => 'katalog?gallery=Tatranská+galéria%2C+TGP',
            ],
            [
                'id'          => 'PGU',
                'lang_string' => 'informacie.info_gallery_PGU',
                'url'         => 'katalog?gallery=Považská+galéria+umenia%2C+PGU',
            ],
            [
                'id'          => 'SGP',
                'lang_string' => 'informacie.info_gallery_SGP',
                'url'         => 'katalog?gallery=Šarišská+galéria%2C+SGP',
            ],
            [
                'id'          => 'MG',
                'lang_string' => 'informacie.info_gallery_MG',
                'url'         => 'katalog?gallery=Moravská galerie, MG',
            ],
            [
                'id'          => 'PNP',
                'lang_string' => 'informacie.info_gallery_PNP',
                'url'         => 'katalog?gallery=Památník+národního+písemnictví%2C+PNP',
            ],
            [
                'id'          => 'RG',
                'lang_string' => 'informacie.info_gallery_4RG',
                'url'         => 'katalog?gallery=Galerie+moderního+umění+v+Roudnici+nad+Labem',
            ],
        ];

        return view('informacie', [
            'items' => $items,
            'galleries' => $galleries,
        ]);
    })->name('frontend.info');

    Route::get('reprodukcie', function (ItemRepository $itemRepository) {
        $collection = Collection::find('55');

        $filter = (new ItemFilter)->setGallery('Slovenská národná galéria, SNG');

        if ($collection) {
            $items_recommended = $collection->items()->inRandomOrder()->take(20)->get();
        } else {
            $items_recommended = $itemRepository->getRandom(20, $filter)->getCollection();
        }

        $response = $itemRepository->getRandom(20, $filter);
        $total = formatNum($response->getTotal());

        return view('reprodukcie', [
            'items_recommended' => $items_recommended,
            'items' => $response->getCollection(),
            'total' => $total,
            'notice' => Notice::current(),
        ]);
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

    Route::get('item', 'ItemController@index')->name('item.index');
    Route::get('item/{id}/show', 'ItemController@show')->name('item.show');
    Route::match(['get', 'post'], 'item/create', 'ItemController@create')->name('item.create');
    Route::match(['get', 'post'], 'item/{id}/edit', 'ItemController@edit')->name('item.edit');

    Route::post('item/destroySelected', 'ItemController@destroySelected');
});

Route::group(['middleware' => ['auth', 'role:admin|editor']], function () {

    Route::post('dielo/{id}/addTags', function($id)
    {
        $item = Item::find($id);
        $newTags = Input::get('tags');

        if (empty($newTags)) {
            Session::flash( 'message', trans('Neboli zadadné žiadne nové tagy.') );
            return Redirect::to($item->getUrl());
        }

        // @TODO take back captcha if opened for all users
        foreach ($newTags as $newTag) {
            $item->tag($newTag);
        }

        Session::flash( 'message', trans('Bolo pridaných ' . count($newTags) . ' tagov. Ďakujeme!') );

        // validate that user is human with recaptcha
        // till it's for authorised users only, temporary disable
        /*
        $secret = config('app.google_recaptcha_secret');
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
        if ($resp->isSuccess()) {
            // add new tags
            foreach ($newTags as $newTag) {
                $item->tag($newTag);
            }
        } else {
            // validation unsuccessful
            return Redirect::to($item->getUrl());
        }
        */

        return Redirect::to($item->getUrl());
    });

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
    Route::get('harvests/harvestFailed/{id}', 'SpiceHarvesterController@harvestFailed');
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
    Route::resource('authority', 'AuthorityController');
    Route::resource('sketchbook', 'SketchbookController');
    Route::resource('notices', 'NoticeController');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

