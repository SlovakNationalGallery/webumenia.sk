<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;

class HeaderImageModel extends Model{

    const IMAGE_PROPERTY = 'main_image';
    const SIZES = [1920, 1400, 1024, 640];
    const DEFAULT_SIZE = 1024;
    const ARTWORKS_DIR = '/images/';


    function uploadHeaderImage(
        UploadedFile $image_file,
        string $filename = null) {
        $image = \Image::make($image_file->getRealPath());
        $image->orientate();

        $parentPath = public_path() . static::ARTWORKS_DIR ;
        $path = public_path() . static::ARTWORKS_DIR . $this->id;

        if (!File::exists($path)) {
            File::makeDirectory($path);
        } else {
            File::cleanDirectory($path);
        }

        $extension = $image_file->getClientOriginalExtension();
        $filename = $this->id . '/'. ($filename ?: md5(date("YmdHis") . rand(5, 50)) . "." . $extension);
        $fullPath = $parentPath . $filename;

        foreach(static::SIZES as $width){
            $image->widen($width);
            if (static::DEFAULT_SIZE === $width){
                $image->save($fullPath);
            }else {
                //replace extension
                $image->save(preg_replace("/(\.[0-9a-z]+$)/i", "." . $width . "$1", $fullPath));
            }
        }
        return $filename;
    }

    public function hasHeaderImage()
    {
        return file_exists(self::getHeaderImagePath());
    }

    function getHeaderImagePath($full = true){
        $filename = $this[static::IMAGE_PROPERTY];
        
        if (!$filename){
            return ($full ? public_path() : '') . static::ARTWORKS_DIR . $this->id . '.jpg'; // fallback for old collections
        }
        $path = ($full ? public_path() : '') . static::ARTWORKS_DIR;
      
        return $path.$filename;
    }

    function getHeaderImageSrcAttribute(){
        $filename = $this[static::IMAGE_PROPERTY];
        
        if (!$filename){
            return  asset(static::ARTWORKS_DIR . $this->id . '.jpg'); // fallback for old collections
        }
        $path =static::ARTWORKS_DIR;
      
        return asset($path.$filename);
    }

    function getHeaderImageSrcsetAttribute(){
        $filename = $this[static::IMAGE_PROPERTY];
        $path = static::ARTWORKS_DIR;
        
        if (!$filename ){
            return ''; // fallback for old collections and articles
        }
        
        $res = asset($path.$filename). ' ' . static::DEFAULT_SIZE . 'w';

        foreach(static::SIZES as $width){
                //replace extension
             $res = $res 
             . ', ' . $path 
             . (static::DEFAULT_SIZE === $width ? $filename : preg_replace("/(\.[0-9a-z]+$)/i", "." . $width . "$1", $filename))
             . ' ' . $width . 'w';
            
        }
        return $res;
    }

    public function getResizedImage($resize)
    {
        $path = self::getHeaderImagePath(false);
        $fullPath = self::getHeaderImagePath();
        $full_path =self::getHeaderImagePath();

        $resize_path = preg_replace("/(\.[0-9a-z]+$)/i", "." . $resize . "$1", $path);
        $resize_full_path = preg_replace("/(\.[0-9a-z]+$)/i", "." . $resize . "$1", $full_path);
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
}
