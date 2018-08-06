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
        $item = Item::find($id);

        if (empty($item->has_iip)) {
            App::abort(404);
        }

        $itemImages = $item->getZoomableImages();
        $index =  0;
        if ($itemImages->count() <= 1 && !empty($item->related_work)) {
            $related_items = Item::related($item, \LaravelLocalization::getCurrentLocale())->with('images')->get();

            $itemImages = collect();
            foreach ($related_items as $related_item) {
                if ($image = $related_item->getZoomableImages()->first()) {
                    $itemImages->push($image);
                }
            }

            $index = $itemImages->search(function (ItemImage $image) use ($item) {
                return $image->item->id == $item->id;
            });
        }

        $fullIIPImgURLs = $itemImages->map(function ($itemImage) {
            return $itemImage->getFullIIPImgURL();
        });

        return view('zoom', [
            'item' => $item,
            'index' => $index,
            'fullIIPImgURLs' => $fullIIPImgURLs,
        ]);
    }
}