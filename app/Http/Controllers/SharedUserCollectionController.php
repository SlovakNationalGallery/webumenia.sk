<?php

namespace App\Http\Controllers;

use App\Item;
use App\SharedUserCollection;
use Illuminate\Http\Request;

class SharedUserCollectionController extends Controller
{
    private $rules = [
        'name' => 'required|string',
        'items' => 'required|array',  // TODO add 'min:1' once WEBUMENIA-1663 is fixed
        'items.*.id' => 'required|distinct'
    ];

    public function create(Request $request)
    {
        $items = Item::whereIn('id', $request->ids)->get();
        return view('frontend.shared-user-collections.show')->with('items', $items);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);
        $collection = SharedUserCollection::create($request->input());

        return redirect()->route(
            'frontend.shared-user-collections.edit', 
            [
                'collection' => $collection,
                'token' => $collection->update_token,
            ]);
    }

    public function show(SharedUserCollection $collection)
    {
        $items = Item::whereIn('id', $collection->items->pluck('id'))->get();

        return view('frontend.shared-user-collections.show', compact('collection', 'items'));
    }

    public function edit(SharedUserCollection $collection, Request $request)
    {
        if ($request->get('token') !== $collection->update_token) {        
            return redirect()
                ->route('frontend.shared-user-collections.show', compact('collection'));
        }

        return view(
            'frontend.shared-user-collections.show', 
            [
                'collection' => $collection,
                'items' => Item::whereIn('id', $collection->items->pluck('id'))->get(),
                'update_token' => $collection->update_token
            ]
        );
    }

    public function update(SharedUserCollection $collection, Request $request)
    {
        if ($request->get('token') !== $collection->update_token) {        
            abort(403);
        }

        $collection->update($request->input());

        return redirect()->route(
            'frontend.shared-user-collections.edit', 
            [
                'collection' => $collection,
                'token' => $collection->update_token,
            ]
        );
    }
}
