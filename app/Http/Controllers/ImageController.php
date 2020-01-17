<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemImage;

class ImageController extends Controller
{
    protected $max_size_to_fit = 800; // width or height in pixels
    const SECONDS_TO_CACHE = 2592000; // 30days (60sec * 60min * 24hours * 30days)

    public function __construct() {
        $this->middleware('throttle:5.1', ['only' => 'download']);
    }

    /**
     * @param int $id
     */
    public function download($id) {
        $image = ItemImage::find($id);

        if (!$image || !$image->item || !$image->item->isFree()) {
            abort(404);
        }

        $image->item->timestamps = false;
        $image->item->download_count += 1;
        $image->item->save();

        $url = sprintf(
            '%s/download/?path=%s',
            config('app.iip_private'),
            urlencode($image->iipimg_url)
        );

        $headers = [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => sprintf(
                'attachment; filename="%s.jpg"',
                basename($image->iipimg_url, '.jp2')
            ),
        ];

        return response()->stream(function () use ($url) {
            readfile($url);
        }, 200, $headers);
    }

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
            $headers['Content-Disposition'] = sprintf('inline; filename="%s"', basename($imagePath));

            return response()->file($imagePath, $headers);
        }

        return abort(404);
    }
}
