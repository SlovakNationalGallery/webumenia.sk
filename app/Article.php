<?php



namespace App;

use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    use \Conner\Tagging\Taggable;
    
    const ARTWORKS_DIR = '/images/clanky/';

    public static $rules = array(
        'slug' => 'required',
        'title' => 'required',
        'summary' => 'required',
        'content' => 'required',
        );

    // public function items()
 //    {
 //        return $this->belongsToMany('Item', 'collection_item', 'collection_id', 'item_id');
 //    }

    public function category()
    {
        return $this->belongsTo(\App\Category::class);
    }

    public function getUrl()
    {
        return URL::to('clanok/' . $this->attributes['slug']);
    }

    public function getShortTextAttribute($string, $length = 160)
    {
        $string = strip_tags($string);
        $string = $string;
        $string = substr($string, 0, $length);
        return substr($string, 0, strrpos($string, ' ')) . " ...";
    }

    public function getHeaderImage($full = false)
    {
        if (empty($this->attributes['main_image'])) {
            return false;
        }

        $relative_path = self::ARTWORKS_DIR . $this->attributes['main_image'];
        if (!file_exists(public_path() . $relative_path) && !$full) {
            $relative_path = self::ARTWORKS_DIR . 'no-image.jpg';
        }
        $path = ($full) ? public_path() . $relative_path : $relative_path;
        return $path;
    }

    public function getResizedImage($resize)
    {
        $file = substr($this->attributes['main_image'], 0, strrpos($this->attributes['main_image'], "."));
        $full_path = public_path() .  self::ARTWORKS_DIR;

        if (!file_exists($full_path . "$file.$resize.jpg")) {
            $img = \Image::make($this->getHeaderImage(true))->fit($resize)->sharpen(7);
            $img->save($full_path . "$file.$resize.jpg");
        }
        $result_path = self::ARTWORKS_DIR .  "$file.$resize.jpg";

        return $result_path;

    }

    public function getThumbnailImage($full = false)
    {
        if (empty($this->attributes['main_image'])) {
            return false;
        }

        $preview_image = substr($this->attributes['main_image'], 0, strrpos($this->attributes['main_image'], ".")); //zmaze priponu
        $preview_image .= '.thumbnail.jpg';
        $relative_path = self::ARTWORKS_DIR . $preview_image;
        $full_path = public_path() . $relative_path;
        if (!file_exists($full_path) && file_exists($this->getHeaderImage(true))) {
            try {
                \Image::make($this->getHeaderImage(true))->fit(600, 250)->save($full_path);
            } catch (Exception $e) {
                
            }
        }
        return $relative_path;
    }

    public function getPublishedDateAttribute($value)
    {
        return Carbon::parse($value)->format('d. m. Y'); //Change the format to whichever you desire
    }

    public function getTitleColorAttribute($value)
    {
        return (!empty($value)) ? $value : '#fff';
    }

    public function getTitleShadowAttribute($value)
    {
        return (!empty($value)) ? $value : '#777';
    }

    public function scopePublished($query)
    {
        return $query->where('publish', '=', 1);
    }

    public function scopePromoted($query)
    {
        return $query->where('promote', '=', 1);
    }

    public function setPublishAttribute($value)
    {
        if ($value && empty($this->attributes['published_date'])) {
            $current_time = Carbon\Carbon::now();
            $this->attributes['published_date'] = $current_time->toDateTimeString();
        }

        $this->attributes['publish'] = (bool)$value;
    }
}
