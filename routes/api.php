<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SharedUserCollectionController;
use App\Http\Controllers\Api\TrackFeaturedPieceClick;
use App\Http\Controllers\Api\V1\ItemController as V1ItemController;
use App\Http\Controllers\Api\V2\ItemController as V2ItemController;

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

Route::post('/track-featured-piece-click', TrackFeaturedPieceClick::class);

Route::resource('/articles', ArticleController::class)
    ->names('api.articles')
    ->only(['show']);

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('items', [V1ItemController::class, 'index'])->name('items.index');
        Route::get('items/aggregations', [V1ItemController::class, 'aggregations'])->name(
            'items.aggregations'
        );
        Route::get('items/suggestions', [V1ItemController::class, 'suggestions'])->name('items.suggestions');
        Route::get('items/{id}', [V1ItemController::class, 'detail'])->name('items.show');
        Route::post('items/{id}/views', [V1ItemController::class, 'incrementViewCount'])
            ->name('items.views');
    });

Route::prefix('v2')->group(function () {
    Route::get('items/{id}', [V2ItemController::class, 'show']);
});
