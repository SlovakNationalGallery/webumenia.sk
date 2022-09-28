<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\ItemResource;
use App\Item;

class ItemController extends Controller
{
    public function detail($id)
    {
        $item = Item::findOrFail($id);
        return new ItemResource($item);
    }
}
