<?php

namespace App\Http\Controllers\Api;

use App\Collection;
use App\Http\Controllers\Controller;
use App\Http\Resources\CollectionResource;

class CollectionController extends Controller
{
    public function index()
    {
        $size = request()->integer('size', 1);
        $paginator = Collection::query()
            ->published()
            ->when(request()->boolean('featured'), fn($query) => $query->where('featured', true))
            ->orderBy('published_at', 'desc')
            ->paginate($size);
        return CollectionResource::collection($paginator);
    }

    public function show(Collection $collection)
    {
        $collection->append('item_filter');
        return new CollectionResource($collection);
    }
}
