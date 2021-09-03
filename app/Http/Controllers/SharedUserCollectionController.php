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
        $items = $this->getItems($request->ids);
        return view('frontend.shared-user-collections.form', [
            'items' => $items,
            'editable' => true,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);
        $collection = SharedUserCollection::create($request->input());

        return redirect()
            ->route(
                'frontend.shared-user-collections.edit', 
                [
                    'collection' => $collection,
                    'token' => $collection->update_token,
                ]
            )
            ->with('created-message', 'Výborne! Vaša kolekcia je pripravená na zdieľanie.'); // TODO i18n
    }

    public function show(SharedUserCollection $collection)
    {
        $items = $this->getItems($collection->items->pluck('id'));

        return view('frontend.shared-user-collections.form', compact('collection', 'items'));
    }

    public function edit(SharedUserCollection $collection, Request $request)
    {
        if ($request->get('token') !== $collection->update_token) {        
            return redirect()
                ->route('frontend.shared-user-collections.form', compact('collection'));
        }

        return view(
            'frontend.shared-user-collections.form', 
            [
                'collection' => $collection,
                'items' => $this->getItems($collection->items->pluck('id')),
                'formAction' => route(
                    'frontend.shared-user-collections.update',
                    [ 
                        'collection' => $collection,
                        'token' => $collection->update_token
                    ]
                ),
                'editable' => true,
                'formMethod' => 'PUT',
            ]
        );
    }

    public function update(SharedUserCollection $collection, Request $request)
    {
        if ($request->get('token') !== $collection->update_token) {        
            abort(403);
        }

        $request->validate($this->rules);
        $collection->update($request->input());

        return redirect()->route(
            'frontend.shared-user-collections.edit', 
            [
                'collection' => $collection,
                'token' => $collection->update_token,
            ]
        );
    }

    private function getItems($ids)
    {
        return Item::with(['translations', 'images'])->whereIn('id', $ids)->get();
    }
}
