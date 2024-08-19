<?php

use App\Http\Controllers\Admin\Authority\RoleTranslationsController;
use App\Http\Controllers\Admin\FeaturedArtworkController;
use App\Http\Controllers\Admin\FeaturedPieceController;
use App\Http\Controllers\Admin\ItemTagsController;
use App\Http\Controllers\Admin\ShuffledItemController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorityController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SketchbookController;
use App\Http\Controllers\SpiceHarvesterController;
use App\Http\Controllers\UserController;
use App\Item;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use UniSharp\LaravelFilemanager\Lfm;

Route::get('admin', [AdminController::class, 'index']);
Route::get('logout', [AuthController::class, 'logout']);
Route::resource('imports', ImportController::class);
Route::get('imports/{import}/launch', [ImportController::class, 'launch']);

Route::group(['middleware' => ['can:edit']], function () {
    Route::get('item/search', [ItemController::class, 'search'])->name('item.search');
    Route::get('item', [ItemController::class, 'index'])->name('item.index');
    Route::resource('item/tags', ItemTagsController::class)->names('item-tags');
    Route::get('item/{id}/show', [ItemController::class, 'show'])->name('item.show');
    Route::match(['get', 'post'], 'item/create', [ItemController::class, 'create'])->name('item.create');
    Route::match(['get', 'post'], 'item/{id}/edit', [ItemController::class, 'edit'])->name('item.edit');
    Route::post('item/destroySelected', [ItemController::class, 'destroySelected']);
    Route::post('dielo/{id}/addTags', function($id) {
        $item = Item::find($id);
        $newTags = Request::input('tags');

        if (empty($newTags)) {
            Session::flash('message', trans('Neboli zadadné žiadne nové tagy.') );
            return Redirect::to($item->getUrl());
        }

        // @TODO take back captcha if opened for all users
        foreach ($newTags as $newTag) {
            $item->tag($newTag);
        }

        Session::flash( 'message', trans('Bolo pridaných ' . count($newTags) . ' tagov. Ďakujeme!') );

        return Redirect::to($item->getUrl());
    });

    Route::resource('article', ArticleController::class);
    Route::get('collection/{collection_id}/detach/{item_id}', [CollectionController::class, 'detach']);
    Route::post('collection/fill', [CollectionController::class, 'fill']);
    Route::post('collection/sort', [CollectionController::class, 'sort']);
    Route::resource('collection', CollectionController::class);
    Route::resource('user', UserController::class);

    Route::group(['prefix' => 'laravel-filemanager'], function () {
        Lfm::routes();
    });
});

Route::group(['middleware' => ['can:administer']], function () {
    Route::resource('featured-pieces', FeaturedPieceController::class);
    Route::resource('featured-artworks', FeaturedArtworkController::class);
    Route::resource('shuffled-items', ShuffledItemController::class);
    Route::get('harvests/launch/{id}', [SpiceHarvesterController::class, 'launch']);
    Route::get('harvests/harvestFailed/{id}', [SpiceHarvesterController::class, 'harvestFailed']);
    Route::get('harvests/orphaned/{id}', [SpiceHarvesterController::class, 'orphaned']);
    Route::get('harvests/{record_id}/refreshRecord/', [SpiceHarvesterController::class, 'refreshRecord']);
    Route::resource('harvests', SpiceHarvesterController::class);
    Route::get('item/backup', [ItemController::class, 'backup']);
    Route::get('item/geodata', [ItemController::class, 'geodata']);
    Route::post('item/refreshSelected', [ItemController::class, 'refreshSelected']);
    Route::get('item/reindex', [ItemController::class, 'reindex']);
    Route::get('authority/reindex', [AuthorityController::class, 'reindex']);
    Route::post('authority/destroySelected', [AuthorityController::class, 'destroySelected']);
    Route::get('authority/search', [AuthorityController::class, 'search']);
    Route::get('authority/role-translations', [RoleTranslationsController::class, 'index'])->name(
        'authority.role-translations.index'
    );
    Route::get('authority/role-translations/download', [RoleTranslationsController::class, 'download'])->name(
        'authority.role-translations.download'
    );
    Route::resource('authority', AuthorityController::class);
    Route::resource('sketchbook', SketchbookController::class);
    Route::resource('notices', NoticeController::class);
    Route::resource('redirects', RedirectController::class);
    Route::get('logs', [LogViewerController::class, 'index']);
});
