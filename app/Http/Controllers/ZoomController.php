<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemImage;

class ZoomController extends Controller
{
   /**
     * Show the zoomable itemImages for the item with specified $id
     *
     * @param  int  $id
     * @return Response
     */
    public function getIndex($id)
    {
        $item = Item::has('images')
            ->with('images')
            ->findOrFail($id);

        $itemImages = $item->images;
        $index =  0;
        
        if ($itemImages->count() === 1 && $item->related_work) {
            $relatedItems = Item::related($item)->has('images')->with('images')->get();

            $itemImages = $relatedItems->map(function ($relatedItem) {
                return $relatedItem->images->first();
            });

            $index = $relatedItems->search(function (Item $relatedItem) use ($item) {
                return $relatedItem->id == $item->id;
            });
        }

        $fullIIPImgURLs = $itemImages->map(function (ItemImage $itemImage) {
            return $itemImage->getDeepZoomUrl();
        });

        return view('zoom', [
            'item' => $item,
            'index' => $index,
            'fullIIPImgURLs' => $fullIIPImgURLs,
        ]);
    }
}
