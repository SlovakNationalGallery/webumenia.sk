<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemImage;

class ZoomController extends Controller
{
   /**
     * Show the zoomable images for the item with specified $id
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

        $images = $item->getZoomableImages();
        $index =  0;
        if ($images->count() <= 1 && !empty($item->related_work)) {
            $related_items = Item::related($item)->with('images')->get();

            $images = collect();
            foreach ($related_items as $related_item) {
                if ($image = $related_item->getZoomableImages()->first()) {
                    $images->push($image);
                }
            }

            $index = $images->search(function (ItemImage $image) use ($item) {
                return $image->item->id == $item->id;
            });
        }

        return view('zoom', array('item' => $item, 'images' => $images, 'index' => $index));
    }
}