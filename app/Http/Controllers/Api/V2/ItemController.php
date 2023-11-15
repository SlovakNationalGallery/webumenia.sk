<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\ItemResource;
use App\Item;

class ItemController extends Controller
{
    public function show($ids)
    {
        $idsArray = explode(',', $ids);
        $items = Item::with('images')->whereIn('id', $idsArray)->get();
        return ItemResource::collection($items);
    }
}
