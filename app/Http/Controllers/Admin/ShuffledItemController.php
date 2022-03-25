<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Item;
use App\ShuffledItem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ShuffledItemController extends Controller
{
    private static $rules = [
        'item_id' => 'required',
        'crop' => 'required|json',
        'is_published' => 'required',
    ];

    // public function index()
    // {
    //     return view('featured_artworks.index', [
    //         'artworks' => FeaturedArtwork::query()
    //             ->with('item')
    //             ->orderBy('id', 'desc')
    //             ->get(),
    //         'lastPublishedId' => FeaturedArtwork::query()
    //             ->published()
    //             ->orderBy('published_at', 'desc')
    //             ->value('id'),
    //     ]);
    // }

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

        $shuffledItem = ShuffledItem::create($request->input());
        $shuffledItem->addMediaFromUrl($shuffledItem->crop_url)->toMediaCollection('image');

        return redirect()->route('shuffled-items.edit', $shuffledItem);
        // TODO
        // ->route('shuffled-items.index')
        // ->with('message', "Vybrané dielo \"{$featuredArtwork->title}\" bolo vytvorené");
    }

    public function update(Request $request, ShuffledItem $shuffledItem)
    {
        $request->validate(self::$rules);
        $request->merge(['crop' => json_decode($request->input('crop'))]);

        $shuffledItem->update($request->input());

        if ($shuffledItem->wasChanged('crop')) {
            $shuffledItem->addMediaFromUrl($shuffledItem->crop_url)->toMediaCollection('image');
        }

        return redirect()
            ->route('shuffled-items.edit', $shuffledItem)
            // TODO
            //     ->route('shuffled-items.index')
            ->with('message', 'Dielo bolo upravené');
    }

    // public function destroy(FeaturedArtwork $featuredArtwork)
    // {
    //     $featuredArtwork->delete();

    //     return redirect()
    //         ->route('featured-artworks.index')
    //         ->with('message', 'Odporúčané dielo bolo zmazané');
    // }
}
