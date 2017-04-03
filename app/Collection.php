<?php

namespace App;

use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic;

class Collection extends \Eloquent
{
    use \Dimsav\Translatable\Translatable;
        
    const ARTWORKS_DIR = '/images/kolekcie/';

    public $translatedAttributes = ['name','type', 'text'];

    public static $rules = array(
        'sk.name' => 'required',
        'sk.text' => 'required',

        'en.name' => 'required',
        'en.text' => 'required',

        'cs.name' => 'required',
        'cs.text' => 'required',
    );

    public static $sortable = array(
        'created_at' => 'dátumu vytvorenia',
        'name' => 'názvu',
    );
    
    public function items()
    {
        return $this->belongsToMany(\App\Item::class, 'collection_item', 'collection_id', 'item_id')->withPivot('order')->orderBy('order', 'asc');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function getPreviewItems()
    {
        
        return $this->items()->limit(10)->get();
    }

    public function getUrl()
    {
        return URL::to('kolekcia/' . $this->attributes['id']);
    }

    public function getShortTextAttribute($string, $length = 160)
    {
        $striped_string = strip_tags(br2nl($string));
        $string = $striped_string;
        $string = substr($string, 0, $length);
        return ($striped_string > $string) ? substr($string, 0, strrpos($string, ' ')) . " ..." : $string;
    }

    public function hasHeaderImage()
    {
        return file_exists(self::getHeaderImageForId($this->id, true));
    }

    public function getHeaderImage($full = false)
    {
        return self::getHeaderImageForId($this->id, $full);
    }

    public static function getHeaderImageForId($id, $full = false)
    {
        $relative_path = self::ARTWORKS_DIR . $id . '.jpg';
        $path = ($full) ? public_path() . $relative_path : $relative_path;
        return $path;
    }

    public function getResizedImage($resize)
    {
        $file =  $this->id;
        $full_path = public_path() .  self::ARTWORKS_DIR;
        if (!file_exists($full_path . "$file.$resize.jpg")) {
            try {
                $img = \Image::make($this->getHeaderImage(true))->fit($resize)->sharpen(7);
            } catch (\Exception $e) {
                $img = \Image::make(public_path() . self::ARTWORKS_DIR . 'no-image.jpg')->fit($resize)->sharpen(7);
            }

            $img->save($full_path . "$file.$resize.jpg");
        }
        $result_path = self::ARTWORKS_DIR .  "$file.$resize.jpg";
        return $result_path;
    }

    public function scopePublished($query)
    {
        return $query->where('publish', '=', 1);
    }

    public function getTitleColorAttribute($value)
    {
        return (!empty($value)) ? $value : '#fff';
    }

    public function getTitleShadowAttribute($value)
    {
        return (!empty($value)) ? $value : '#777';
    }
}
