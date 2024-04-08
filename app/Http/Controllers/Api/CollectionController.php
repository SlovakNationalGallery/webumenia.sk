<?php

namespace App\Http\Controllers\Api;

use App\Collection;
use App\Http\Controllers\Controller;
use App\Http\Resources\CollectionResource;

class CollectionController extends Controller
{
    public function index()
    {
        $paginator = Collection::query()
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate();
        $paginator->getCollection()->each->append('filter_items_count');
        return CollectionResource::collection($paginator);
    }

    public function show(Collection $collection)
    {
        $collection->append('filter_items_url');
        return new CollectionResource($collection);
    }
}
