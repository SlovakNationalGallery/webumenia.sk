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

use App\Collection;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Facades\Experiment;
use App\Filter\ItemFilter;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ClanokController;
use App\Http\Controllers\EducationalArticleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\KolekciaController;
use App\Http\Controllers\PatternlibController;
use App\Http\Controllers\SharedUserCollectionController;
use App\Http\Controllers\SkicareController;
use App\Http\Controllers\UserCollectionController;
use App\Http\Controllers\NewCatalogController;
use App\Http\Controllers\ZoomController;
use App\Http\Middleware\ApplyFrontendScope;
use App\Http\Middleware\RedirectLegacyCatalogRequest;
use App\Item;
use App\Notice;
use App\Order;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect',
        ApplyFrontendScope::class,
    ],
],
function()
{
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('leto', function () {

        return redirect('kolekcia/25');
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
    })->name('frontend.reproduction.detail');

    Route::post('objednavka', function () {

        $input = Request::all();

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
            $order->name = Request::input('name');
            $order->address = Request::input('address');
            $order->email = Request::input('email');
            $order->phone = Request::input('phone');
            $order->format = Request::input('format');
            $order->frame = Request::input('frame');
            $order->purpose_kind = Request::input('purpose_kind');
            $order->purpose = Request::input('purpose');
            $order->delivery_point = Request::input('delivery_point', null);
            $order->note = Request::input('note');
            $order->save();

            $item_ids = explode(', ', Request::input('pids'));

            foreach ($item_ids as $item_id) {
                $order->items()->attach($item_id);
            }

            $type = (Request::input('format') == 'digitálna reprodukcia') ? 'digitálna' : 'tlačená';

            //poslat objednavku do Jiry
            $client = new GuzzleHttp\Client();
            $res = $client->post('https://jira.sng.sk/rest/cedvu/latest/order/create', [
                'auth' => [Config::get('app.jira_auth.user'), Config::get('app.jira_auth.pass')],
                'form_params' => [
                    'pids' => Request::input('pids'),
                    'organization' => $order->name,
                    'contactPerson' => $order->name,
                    'email' => $order->email,
                    'kindOfPurpose' => $order->purpose_kind,
                    'purpose' => $order->purpose,
                    'medium' => 'Iné',
                    'address' => $order->address,
                    'phone' => $order->phone,
                    'ico' => '',
                    'dic' => '',
                    'numOfCopies' => '1',
                    'reproductionType' => $type,
                    'format' => $order->format,
                    'frameColor' => $order->frame,
                    'deliveryPoint' => $order->delivery_point,
                    'note' => $order->note,
                ],
            ]);
            if ($res->getStatusCode() == 200) {
                Session::forget('cart');

                return redirect('dakujeme');
            } else {
                Session::flash('message', 'Nastal problém pri uložení vašej objednávky.');

                return Redirect::back()->withInput();
            }
        }

        return Redirect::back()->withInput()->withErrors($v);

    })->name('objednavka.post');

    Route::get('dakujeme', function () {

        return view('dakujeme');
    });

    Route::get('dielo/{id}/zoom', [ZoomController::class, 'getIndex'])->name('item.zoom');

    Route::get('ukaz_skicare', [SkicareController::class, 'index']);
    Route::get('skicare', [SkicareController::class, 'getList']);
    Route::get('dielo/{id}/skicar', [SkicareController::class, 'getZoom']);

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

    Route::get('dielo/{id}/stiahnut', function ($id) {
        $item = Item::findOrFail($id);
        if ($item->images->isEmpty()) {
            abort(404);
        }

        return redirect()->route('image.download', ['id' => $item->images->first()->id]);
    })->middleware('throttle:downloads');

    Route::get('dielo/{id}', function ($id, ItemRepository $itemRepository) {
        /** @var Item $item */
        $item = Item::find($id);
        if (empty($item)) {
            abort(404);
        }

        $item->timestamps = false;
        $item->view_count++;
        $item->last_viewed_at = Date::now();
        $item->saveQuietly(); // Do not sync to search

        $previous = $next = false;

        $similar_items = Item::with('translations')
            ->whereIn(
                'id',
                $itemRepository
                    ->getSimilar(12, $item)
                    ->getCollection()
                    ->pluck('id')
            )
            ->get();
        $related_items = !empty($item->related_work)
            ? $item
                ->related()
                ->with('translations')
                ->take(10)
                ->get()
            : null;

        if (Request::has('collection')) {
            $collection = Collection::find((int) Request::input('collection'));
            if (!empty($collection)) {
                $items = $collection->items->pluck('id')->all();
                $previousId = getPrevVal($items, $id);
                if ($previousId) {
                    $previous = Item::find($previousId)->getUrl([
                        'collection' => $collection->id,
                    ]);
                }
                $nextId = getNextVal($items, $id);
                if ($nextId) {
                    $next = Item::find($nextId)->getUrl(['collection' => $collection->id]);
                }
            }
        }

        $gtm_data_layer = [
            'artwork' => [
                'authors' => array_values($item->authors),
                'work_types' => collect($item->work_type_tree)->pluck(['path']),
                'topic ' => $item->topic,
                'media' => $item->mediums,
                'technique' => $item->technique,
                'related_work' => $item->related_work,
            ],
        ];

        return view(
            'dielo',
            compact(
                'item',
                'similar_items',
                'related_items',
                'previous',
                'next',
                'gtm_data_layer'
            )
        );
    })->name('dielo');

    Route::get('dielo/{id}/colorrelated', function ($id, ItemRepository $itemRepository) {
        $item = Item::findOrFail($id);
        return view('dielo-colorrelated', [
            'similar_by_color' => Item::with('translations')
                ->whereIn(
                    'id',
                    $itemRepository
                        ->getSimilarByColor(12, $item)
                        ->getCollection()
                        ->pluck('id')
                )
                ->get(),
        ]);
    })->name('dielo.colorrelated');

    Route::get('patternlib', [PatternlibController::class, 'getIndex'])->name('frontend.patternlib.index');

    Route::get('katalog', [NewCatalogController::class, 'index'])
        ->middleware(RedirectLegacyCatalogRequest::class)
        ->name('frontend.catalog.index');
        
    Route::get('katalog-old', [CatalogController::class, 'getIndex'])
        ->name('frontend.catalog.old');
        
    Route::get('katalog/suggestions', [CatalogController::class, 'getSuggestions'])->name('frontend.catalog.suggestions');
    Route::get('katalog/random', [CatalogController::class, 'getRandom'])->name('frontend.catalog.random');

    Route::match(array('GET', 'POST'), 'autori', [AuthorController::class, 'getIndex'])->name('frontend.author.index');
    Route::match(array('GET', 'POST'), 'autori/suggestions', [AuthorController::class, 'getSuggestions'])->name('frontend.author.suggestions');
    Route::get('autor/{id}', [AuthorController::class, 'getDetail'])->name('frontend.author.detail');

    Route::match(array('GET', 'POST'), 'clanky', [ClanokController::class, 'getIndex'])->name('frontend.article.index');
    Route::match(array('GET', 'POST'), 'clanky/suggestions', [ClanokController::class, 'getSuggestions']);
    Route::get('clanok/{slug}', [ClanokController::class, 'getDetail'])->name('frontend.article.detail');

    Route::match(array('GET', 'POST'), 'kolekcie', [KolekciaController::class, 'getIndex'])->name('frontend.collection.index');
    Route::match(array('GET', 'POST'), 'kolekcie/suggestions', [KolekciaController::class, 'getSuggestions'])->name('frontend.collection.suggestions');
    Route::get('kolekcia/{slug}', [KolekciaController::class, 'getDetail'])->name('frontend.collection.detail');
    Route::get('oblubene', [UserCollectionController::class, 'show'])->name('frontend.user-collection.show');
    Route::resource('oblubene', SharedUserCollectionController::class)
        ->names('frontend.shared-user-collections')
        ->parameters(['oblubene' => 'collection:public_id'])
        ->except('index');
    Route::resource('edu', EducationalArticleController::class)
        ->names('frontend.educational-article')
        ->parameters(['edu' => 'article:slug']);

    Route::get('informacie', function (ItemRepository $itemRepository) {
        $for_reproduction_filter = (new ItemFilter)->setHasImage(true)->setHasIip(true)->setIsForReproduction(true);
        $items_for_reproduction_search = $itemRepository->getRandom(20, $for_reproduction_filter);
        $items_for_reproduction_total =  formatNum($itemRepository->count((new ItemFilter)->setIsForReproduction(true)));
        $items_for_reproduction_sample = $items_for_reproduction_search->getCollection();

        $galleries = config('galleries');

        return view('informacie', compact(
            'galleries',
            'items_for_reproduction_sample',
            'items_for_reproduction_total'
        ));
    })->name('frontend.info');

    Route::get('reprodukcie', function (ItemRepository $itemRepository) {
        $collection = Collection::find('55');

        $filter = (new ItemFilter)->setIsForReproduction(true)->setHasImage(true)->setHasIip(true);

        if ($collection) {
            $items_recommended = $collection->items()->inRandomOrder()->take(20)->get();
        } else {
            $items_recommended = $itemRepository->getRandom(20, $filter)->getCollection();
        }

        $response = $itemRepository->getRandom(20, $filter);
        $total =  formatNum($itemRepository->count((new ItemFilter)->setIsForReproduction(true)));

        return view('reprodukcie', [
            'items_recommended' => $items_recommended,
            'items' => $response->getCollection(),
            'total' => $total,
            'notice' => Notice::current(),
        ]);
    })->name('frontend.reproduction.index');
});

Route::get('dielo/nahlad/{id}/{width}/{height?}', [ImageController::class, 'resize'])
    ->where('width', '[0-9]+')
    ->where('height', '[0-9]+')
    ->name('dielo.nahlad');
Route::get('image/{id}/download', [ImageController::class, 'download'])
    ->name('image.download');

Route::group(array('middleware' => 'guest'), function () {
    Route::get('login', [AuthController::class, 'getLogin']);
    Route::post('login', [AuthController::class, 'postLogin']);
});
