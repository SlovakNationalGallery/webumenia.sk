<?php



namespace App;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Article extends Model implements TranslatableContract
{
    use Translatable;

    use \Conner\Tagging\Taggable;

    use HasHeaderImageTrait;

    function getArtworksDirAttribute()
    {
        return '/images/clanky/';
    }

    public $translatedAttributes = ['title', 'summary', 'content'];


    public static $rules = array(
        'slug'       => 'required',
        'author'     => 'required',

        'sk.title'   => 'required',
        'sk.summary' => 'required',
        'sk.content' => 'required',
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

    public function getTitleWithCategoryAttribute()
    {
        $string = $this->title;
        if ($this->category) {
            $string = $this->category->name . ': ' . $string;
        }
        return $string;
    }

    public function getShortTextAttribute($string, $length = 160)
    {
        $string = strip_tags($string);
        $string = $string;
        $string = substr($string, 0, $length);
        return substr($string, 0, strrpos($string, ' ')) . " ...";
    }

    public function getThumbnailImage($full = false)
    {
        $path = self::getHeaderImagePath($full);
        $full_path = self::getHeaderImagePath();

        if (!file_exists($full_path)) {
            return false;
        }
        $preview_path = preg_replace("/(\.[0-9a-z]+$)/i", ".thumbnail" . "$1", $path);

        $preview_full_path = preg_replace("/(\.[0-9a-z]+$)/i", ".thumbnail" . "$1", $full_path);
        if (!file_exists($preview_full_path)) {
            try {
                \Image::make($full_path)->fit(600, 250)->save($preview_full_path);
            } catch (\Exception $e) {
                app('sentry')->captureException($e);
            }
        }
        return $preview_path;
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

    public function getContentImages()
    {
        return array_merge(
            parseUrls($this->summary),
            parseUrls($this->content)
        );
    }

    public function scopePromoted($query)
    {
        return $query->where('promote', '=', 1);
    }

    public function setPublishAttribute($value)
    {
        if ($value && empty($this->attributes['published_date'])) {
            $current_time = Carbon::now();
            $this->attributes['published_date'] = $current_time->toDateTimeString();
        }

        $this->attributes['publish'] = (bool)$value;
    }

    
    public function getReadingTimeAttribute(){
        return getEstimateReadingTime($this->summary . ' ' . $this->content, App::getLocale() );
    }
}
