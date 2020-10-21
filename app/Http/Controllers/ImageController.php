<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Item;

class ImageController extends Controller
{
    protected $max_size_to_fit = 800; // width or height in pixels

    public function resize($id, $width, $height=null)
    {
        if (
            ($width <= $this->max_size_to_fit) &&
            ($height <= $this->max_size_to_fit) &&
            Item::where('id', '=', $id)->exists()
        ) {
            // disable resizing when requesting 800px width
            $resize = ($width == $this->max_size_to_fit) ? false : $width;
            $resize_method = 'widen';

            if ($height) {
                $resize_method = 'heighten';
                $resize = ($height == $this->max_size_to_fit) ? false : $height;
            }

            $imagePath = public_path() . Item::getImagePathForId($id, false, $resize, $resize_method);

            return response()->file($imagePath);
        }

        return abort(404);
    }
}
