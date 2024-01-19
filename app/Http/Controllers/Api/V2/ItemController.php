<?php

namespace App\Http\Controllers\Api\V2;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\ItemResource;
use App\Item;

class ItemController extends Controller
{
    public function __construct(private readonly ItemRepository $itemRepository)
    {
    }

    public function show($id)
    {
        $item = Item::with('images')->findOrFail($id);
        return new ItemResource($item);
    }

    public function suggestions()
    {
        $size = (int) request()->get('size', 1);
        $search = (string) request()->get('search');
        $results = $this->itemRepository
            ->getSuggestions($size, $search)
            ->getCollection();
        $items = Item::query()
            ->with(['images', 'authorities'])
            ->whereIn('id', $results->pluck('id'))
            ->get();
        return ItemResource::collection($items);
    }
}
