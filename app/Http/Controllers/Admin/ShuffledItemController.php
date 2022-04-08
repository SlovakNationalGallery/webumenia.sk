<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Item;
use App\ShuffledItem;
use Illuminate\Http\Request;

class ShuffledItemController extends Controller
{
    private static $rules = [
        'item_id' => 'required',
        'crop' => 'json|required',
        'is_published' => 'required',
        '*.filters.*.url' => 'url|required',
        '*.filters.*.attributes.*.name' => 'string|required',
        '*.filters.*.attributes.*.label' => 'string|required',
    ];

    public function index()
    {
        return view('shuffled-items.index', [
            'shuffledItems' => ShuffledItem::query()
                ->with(['item', 'media', 'translations'])
                ->orderBy('id', 'desc')
                ->get(),
        ]);
    }

    public function create(Request $request)
    {
        $item = $request->query('itemId') ? Item::find($request->query('itemId')) : null;

        $shuffledItem = new ShuffledItem();

        $request->whenHas('itemId', function ($itemId) use ($shuffledItem) {
            $shuffledItem->item = Item::findOrFail($itemId);
        });

        return view('shuffled-items.form', compact('shuffledItem'));
    }

    public function edit(ShuffledItem $shuffledItem)
    {
        return view('shuffled-items.form', compact('shuffledItem'));
    }

    public function store(Request $request)
    {
        $request->validate(self::$rules);
        $request->merge(['crop' => json_decode($request->input('crop'))]);

        $shuffledItem = ShuffledItem::create($request->input());
        $shuffledItem->addMediaFromUrl($shuffledItem->crop_url)->toMediaCollection('image');

        return redirect()
            ->route('shuffled-items.index')
            ->with('message', 'Náhodné dielo bolo vytvorené');
    }

    public function update(Request $request, ShuffledItem $shuffledItem)
    {
        $request->validate(self::$rules);
        $request->merge(['crop' => json_decode($request->input('crop'))]);

        // Delete translations so that removed filters are deleted
        $shuffledItem->deleteTranslations();
        $shuffledItem->update($request->input());

        if ($shuffledItem->wasChanged('crop')) {
            $shuffledItem->addMediaFromUrl($shuffledItem->crop_url)->toMediaCollection('image');
        }

        return redirect()
            ->route('shuffled-items.index')
            ->with('message', 'Dielo bolo upravené');
    }

    public function destroy(ShuffledItem $shuffledItem)
    {
        $shuffledItem->delete();

        return redirect()
            ->route('shuffled-items.index')
            ->with('message', 'Náhodné dielo bolo zmazané');
    }
}
