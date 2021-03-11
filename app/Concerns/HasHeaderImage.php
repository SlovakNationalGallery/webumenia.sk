<?php

namespace App\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait HasHeaderImage
{
    static $SIZES = [1920, 1400, 1024, 640];
    static $DEFAULT_SIZE = 1024;

    public function getArtworksDirAttribute()
    {
        return '/images/';
    }

    public function getImagePropertyAttribute()
    {
        return 'main_image';
    }


    public function uploadHeaderImage(
        UploadedFile $image_file,
        string $filename = null
    ) {
        $image = \Image::make($image_file->getRealPath());
        $image->orientate();

        $parentPath = public_path() . $this->artworks_dir;
        $path = public_path() . $this->artworks_dir . $this->id;

        if (!File::exists($path)) {
            File::makeDirectory($path);
        } else {
            File::cleanDirectory($path);
        }

        $extension = $image_file->getClientOriginalExtension();
        $filename = $this->id . '/' . ($filename ?: md5(date("YmdHis") . rand(5, 50)) . "." . $extension);
        $fullPath = $parentPath . $filename;

        foreach (static::$SIZES as $width) {
            $image->widen($width);
            if (static::$DEFAULT_SIZE === $width) {
                $image->save($fullPath);
            } else {
                //replace extension
                $image->save(Str::replaceLast('.', ".". $width.".", $fullPath));
            }
        }
        return $filename;
    }

    public function hasHeaderImage()
    {
        return file_exists($this->getHeaderImagePath());
    }

    public function getHeaderImagePath($full = true)
    {
        $filename = $this[$this->image_property];

        if (!$filename) {
            return ($full ? public_path() : '') . $this->artworks_dir . $this->id . '.jpg'; // fallback for old collections
        }
        $path =  ($full ? public_path() : '') . $this->artworks_dir;

        return $path . $filename;
    }

    public function getHeaderImageSrcAttribute()
    {
        $filename = $this[$this->image_property];

        if (!$filename) {
            return  asset($this->artworks_dir . $this->id . '.jpg'); // fallback for old collections
        }
        $path = $this->artworks_dir;

        return asset($path . $filename);
    }

    public function getHeaderImageSrcsetAttribute()
    {
        $filename = $this[$this->image_property];
        $path = $this->artworks_dir;

        if (!$filename) {
            return ''; // fallback for old collections and articles
        }

        $res = asset($path . $filename) . ' ' . static::$DEFAULT_SIZE . 'w';

        foreach (static::$SIZES as $width) {
            //replace extension
            $res = $res
                . ', ' . $path
                . (static::$DEFAULT_SIZE === $width ? $filename : Str::replaceLast('.', ".". $width.".", $filename))
                . ' ' . $width . 'w';
        }
        return $res;
    }

    public function getResizedImage($resize)
    {
        $path = $this->getHeaderImagePath(false);
        $full_path = $this->getHeaderImagePath();

        $resize_path = Str::replaceLast('.',".". $resize .".", $path);
        $resize_full_path = Str::replaceLast('.', ".". $resize .".", $full_path);
        if (!file_exists($resize_full_path)) {
            try {
                $img = \Image::make($this->getHeaderImagePath())->fit($resize)->sharpen(7);
            } catch (\Exception $e) {
                $img = \Image::make(public_path('images/no-image/no-image.jpg'))->fit($resize)->sharpen(7);
            }

            $img->save($resize_full_path);
        }
        return $resize_path;
    }

    public function getThumbnailImage($full = false)
    {
        $path = $this->getHeaderImagePath($full);
        $full_path = $this->getHeaderImagePath();

        if (!file_exists($full_path)) {
            return false;
        }
        $preview_path = Str::replaceLast('.', ".thumbnail.", $path);
        $preview_full_path = Str::replaceLast('.', ".thumbnail.", $full_path);

        if (!file_exists($preview_full_path)) {
            try {
                \Image::make($full_path)->fit(600, 250)->save($preview_full_path);
            } catch (\Exception $e) {
                app('sentry')->captureException($e);
            }
        }
        return $preview_path;
    }
}
