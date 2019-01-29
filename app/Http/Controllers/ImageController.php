<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Item;

class ImageController extends Controller
{
    protected $max_size_to_fit = 800; // width or height in pixels
    const SECONDS_TO_CACHE = 2592000; // 30days (60sec * 60min * 24hours * 30days)

    public function resize($id, $width, $height=null)
    {
        $headers = ['Cache-Control' => 'max-age=' . self::SECONDS_TO_CACHE];

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

            return response()->file($imagePath, $headers);
        }

        return App::abort(404);
    }
}
