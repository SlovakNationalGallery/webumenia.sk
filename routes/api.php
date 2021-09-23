<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Item;
use Api\SharedUserCollectionController;
use ElasticScoutDriverPlus\Exceptions\QueryBuilderException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('/shared-user-collections', SharedUserCollectionController::class)
    ->names('api.shared-user-collections')
    ->parameters(['shared-user-collections' => 'collection:public_id']);

Route::get('/items', function (Request $request) {
    $filter = (array)$request->get('filter');
    $sort = (array)$request->get('sort');
    $size = (int)$request->get('size', 1);

    try {
        $query = Item::filterQuery($filter)->buildQuery();
    } catch (QueryBuilderException $e) {
        $query = ['match_all' => new \stdClass];
    }

    $searchRequest = Item::rawSearch()->query($query);

    collect($sort)
        ->only(Item::$sortables)
        ->filter(function ($direction) {
            return in_array($direction, ['asc', 'desc'], true);
        })
        ->each(function ($direction, $field) use ($searchRequest) {
            $searchRequest->sort($field, $direction);
        });

    $items = $searchRequest->paginate($size);
    return response()->json($items);
});

Route::get('/items/aggregations', function (Request $request) {
    $filter = (array)$request->get('filter');
    $terms = (array)($request->get('terms'));
    $min = (array)$request->get('min');
    $max = (array)$request->get('max');
    $size = (int)$request->get('size', 1);

    try {
        $query = Item::filterQuery($filter)->buildQuery();
    } catch (QueryBuilderException $e) {
        $query = ['match_all' => new \stdClass];
    }

    $searchRequest = Item::rawSearch()->query($query);

    foreach ($terms as $agg => $field) {
        $searchRequest->aggregate($agg, [
            'terms' => [
                'field' => $field,
                'size' => $size,
            ]
        ]);
    }

    foreach ($min as $agg => $field) {
        $searchRequest->aggregate($agg, [
            'min' => [
                'field' => $field,
            ]
        ]);
    }

    foreach ($max as $agg => $field) {
        $searchRequest->aggregate($agg, [
            'max' => [
                'field' => $field,
            ]
        ]);
    }

    $searchResult = $searchRequest->execute();
    return response()->json($searchResult->aggregations());
});

Route::get('/items/{id}', function (Request $request, $id) {
    $items = Item::idsSearch()
        ->values([(string)$id])
        ->execute();

    if (!$items->total()) {
        abort(404);
    }

    return response()->json($items->matches()->first());
});
