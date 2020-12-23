<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{

    protected $fillable = array(
        'url',
        'label',
        'locale',
    );

    public static $rules = array(
        'url' => 'url|required',
        'locale' => 'in:sk,en|required',
    );

    public function linkable()
    {
        return $this->morphTo();
    }

    public function getIsVideoAttribute()
    {
        return self::isVideo($this->url);
    }

    public function getEmbedAttribute()
    {
        $embed = \Embed::make($this->url)->parseUrl();
        if ($embed) {
            $embed->setAttribute(['width' => 600]);
            return $embed->getHtml();
        }
        return null;
    }

    public static function parse($url)
    {
        $url_parts = parse_url($url);
        return $url_parts['host'];
    }

    public static function isVideo($url) {
        if (preg_match ("/\b(?:vimeo|youtube|dailymotion)\.com\b/i", $url)) {
           return true;
        }
        return false;
    }
}
