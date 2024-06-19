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
        $validated = $request->validate([
            'ids' => ['array'],
            'ids.*' => ['required', 'string'],
            'size' => ['integer'],
        ]);

        $items = Item::with(['images', 'authorities'])
            ->when(
                $request->filled('ids'),
                fn ($query) => $query->whereIn('id', $validated['ids'])
            )
            ->paginate($validated['size'] ?? null);

        return ItemResource::collection($items);
    }
}
