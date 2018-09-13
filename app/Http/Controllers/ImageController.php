<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Item;

class ImageController extends Controller
{
    protected $max_width = 800;

    public function resize($id, $width, $height=null)
    {
        if (
            ($width <= $this->max_width) &&
            ($height <= $this->max_width) &&
            Item::where('id', '=', $id)->exists()
        ) {
            // disable resizing when requesting 800px width
            $resize = ($width == $this->max_width) ? false : $width;
            $resize_method = 'widen';

            if ($height) {
                $resize_method = 'heighten';
                $resize = $height;
            }

            $imagePath = public_path() . Item::getImagePathForId($id, false, $resize, $resize_method);

            return response()->file($imagePath);
        }

        return App::abort(404);
    }
}
