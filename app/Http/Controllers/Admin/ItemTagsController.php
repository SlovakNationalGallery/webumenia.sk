<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;

class ItemTagsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|string',
            'tags' => 'required|array',
            'tags.*' => 'required|string',
        ]);

        $items = Item::whereIn('id', $request->input('ids'));
        $tags = $request->input('tags');

        foreach ($items->lazy() as $item) {
            $item->tag($tags);
        }

        return back()->withMessage('Tagy boli pridané');
    }
}
