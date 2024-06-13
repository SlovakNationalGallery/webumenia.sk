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
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'Invalid input'], 400);
        }

        $size = $request->input('size', 15);
        
        $items = Item::with(['images', 'authorities'])
            ->whereIn('id', $ids)
            ->paginate($size);

        return ItemResource::collection($items);
    }
}
