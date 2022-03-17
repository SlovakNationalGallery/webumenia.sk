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

    // public function edit(FeaturedArtwork $featuredArtwork)
    // {
    //     return view('featured_artworks.form', [
    //         'artwork' => $featuredArtwork,
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $request->validate(self::$rules);
    //     $featuredArtwork = FeaturedArtwork::create(
    //         array_merge($request->input(), [
    //             'is_published' => $request->boolean('is_published'),
    //         ])
    //     );

    //     return redirect()
    //         ->route('featured-artworks.index')
    //         ->with('message', "Vybrané dielo \"{$featuredArtwork->title}\" bolo vytvorené");
    // }

    // public function update(Request $request, FeaturedArtwork $featuredArtwork)
    // {
    //     $request->validate(self::$rules);

    //     $featuredArtwork->update(
    //         array_merge($request->input(), [
    //             'is_published' => $request->boolean('is_published'),
    //         ])
    //     );

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
