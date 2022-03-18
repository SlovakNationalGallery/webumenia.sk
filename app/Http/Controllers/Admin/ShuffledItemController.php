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
        'image' => ['image', 'dimensions:min_width=1200'],
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
        // 'image' parameter is required when creating
        $rules = array_merge_recursive(self::$rules, ['image' => ['required']]);

        $request->validate($rules);
        $shuffledItem = ShuffledItem::create($request->input());
        $shuffledItem->addMediaFromRequest('image')->toMediaCollection('image');

        return redirect()->route('shuffled-items.edit', $shuffledItem);
        // TODO
        // ->route('shuffled-items.index')
        // ->with('message', "Vybrané dielo \"{$featuredArtwork->title}\" bolo vytvorené");
    }

    // public function update(Request $request, ShuffledItem $shuffledItem)
    // {
    //     $request->validate(self::$rules);

    //     $featuredArtwork->update(
    //         array_merge($request->input(), [
    //             'is_published' => $request->boolean('is_published'),
    //         ])
    //     );
    //     $request->whenHas('image', function () use ($shuffledItem) {
    //         $shuffledItem->addMediaFromRequest('image')->toMediaCollection('image');
    //     });

    //     return redirect()
    //         ->route('featured-artworks.index')
    //         ->with('message', "Vybrané dielo \"{$featuredArtwork->title}\" bolo upravené");
    // }

    // public function destroy(FeaturedArtwork $featuredArtwork)
    // {
    //     $featuredArtwork->delete();

    //     return redirect()
    //         ->route('featured-artworks.index')
    //         ->with('message', 'Odporúčané dielo bolo zmazané');
    // }
}
