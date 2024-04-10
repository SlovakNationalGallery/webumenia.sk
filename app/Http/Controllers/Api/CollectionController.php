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
        return CollectionResource::collection($paginator);
    }

    public function show(Collection $collection)
    {
        $collection->append('item_filter');
        return new CollectionResource($collection);
    }
}
