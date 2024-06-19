<?php

namespace App\Http\Controllers\Api\V2;

use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\ItemResource;

class ItemController extends Controller
{
    public function show($id)
    {
        $item = Item::with('images')->findOrFail($id);
        return new ItemResource($item);
    }

    public function index(Request $request)
    {
        $size = $request->input('size', 15);

        $items = Item::with(['images', 'authorities'])
            ->when(
                $request->filled('ids'),
                fn ($query) => $query->whereIn('id', (array) $request->input('ids'))
            )
            ->paginate($size);

        return ItemResource::collection($items);
    }
}
