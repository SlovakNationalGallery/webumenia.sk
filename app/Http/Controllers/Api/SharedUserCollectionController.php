<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SharedUserCollection;

class SharedUserCollectionController extends Controller
{
    public function show(SharedUserCollection $collection)
    {
        return [
            'name' => $collection->name,
            'author' => $collection->author,
            'created_at' => $collection->created_at,
            'items_count' => $collection->items->count(),
        ];
    }
}
