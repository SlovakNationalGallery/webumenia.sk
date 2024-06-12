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

    public function showMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'Invalid input'], 400);
        }

        $items = Item::with('images')->whereIn('id', $ids)->get();

        return ItemResource::collection($items);
    }
}
