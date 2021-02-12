<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

class UserCollectionController extends Controller
{

    public function show(Request $request)
    {
        $ids = $request->input('ids', []);
        $items = Item::whereIn('id', $ids)->paginate(24);
        $items->appends(compact('ids'));

        return view('frontend.user-collection.show', compact('items'));
    }
}
