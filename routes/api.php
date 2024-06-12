<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\CollectionItemController;
use App\Http\Controllers\Api\NewsletterSubscriptionController;
use App\Http\Controllers\Api\SharedUserCollectionController;
use App\Http\Controllers\Api\TrackFeaturedPieceClick;
use App\Http\Controllers\Api\V1\ItemController as V1ItemController;
use App\Http\Controllers\Api\V2\ItemController as V2ItemController;
use Illuminate\Support\Facades\Route;

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

Route::resource('/collections', CollectionController::class)
    ->names('api.collections')
    ->only(['index', 'show']);

Route::resource('/collections/{collection}/items', CollectionItemController::class)
    ->names('api.collections.items')
    ->only(['index']);

Route::resource('/newsletter-subscriptions', NewsletterSubscriptionController::class)->names(
    'api.newsletter-subscriptions'
);
Route::post('/newsletter-subscriptions/dismissals', [
    NewsletterSubscriptionController::class,
    'storeDismissal',
])->name('api.newsletter-subscriptions.dismiss');

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::get('items', [V1ItemController::class, 'index'])->name('items.index');
        Route::get('items/aggregations', [V1ItemController::class, 'aggregations'])->name(
            'items.aggregations'
        );
        Route::get('items/catalog-title', [V1ItemController::class, 'catalogTitle'])->name(
            'items.catalog-title'
        );
        Route::get('items/suggestions', [V1ItemController::class, 'suggestions'])->name(
            'items.suggestions'
        );
        Route::get('items/{id}', [V1ItemController::class, 'detail'])->name('items.show');
        Route::get('items/{id}/similar', [V1ItemController::class, 'similar'])->name(
            'items.similar'
        );
        Route::post('items/{id}/views', [V1ItemController::class, 'incrementViewCount'])->name(
            'items.views'
        );
    });

Route::prefix('v2')->group(function () {
    Route::get('items/{id}', [V2ItemController::class, 'show']);
    Route::get('items', [V2ItemController::class, 'showMultiple']);
});
