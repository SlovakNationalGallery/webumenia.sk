<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

class UserCollectionController extends Controller
{

    public function show(Request $request)
    {
        $items = Item::whereIn('id', $request->input('ids', []))->simplePaginate(24);
        return view('frontend.user-collection.show', compact('items'));
    }
}
