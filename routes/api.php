<?php

use Illuminate\Support\Facades\Route;
use Api\SharedUserCollectionController;
use App\Http\Controllers\Api\ItemController;

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

Route::prefix('v1')->group(function () {
    Route::get('items', [ItemController::class, 'index']);
    Route::get('items/aggregations', [ItemController::class, 'aggregations']);
    Route::get('items/{id}', [ItemController::class, 'detail']);
});
