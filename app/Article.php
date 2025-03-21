<?php

namespace App;

use App\Concerns\HasHeaderImage;
use App\View\Components\Teaser;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model implements TranslatableContract
{
    use Translatable;
    use HasFactory;
    use SoftDeletes;

    use \Conner\Tagging\Taggable;

    use HasHeaderImage;

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('home.articles'));
    }

    function getArtworksDirAttribute()
    {
        return '/images/clanky/';
    }

    public $translatedAttributes = ['title', 'summary', 'content'];

    protected $fillable = ['published_date'];

    protected $casts = [
        'edu_media_types' => 'array',
        'edu_target_age_groups' => 'array',
        'edu_keywords' => 'array',
        'edu_suitable_for_home' => 'boolean',
        'published_date' => 'datetime',
    ];

    public static $eduMediaTypes = [
        'methodology',
        'worksheet',
        'video',
        'collection',
        'workshop',
        'virtual_exhibition',
        'activities',
    ];

    public static $eduAgeGroups = ['all-ages', '3-6', '7-10', '11-15', '16-19'];

    public static function getValidationRules()
    {
        return [
            'slug' => 'required',
            'author' => 'required',
            'sk.title' => 'required',
            'sk.summary' => 'required',
            'sk.content' => 'required',
            'edu_media_types' => ['array', Rule::in(self::$eduMediaTypes)],
            'edu_target_age_groups' => ['array', Rule::in(self::$eduAgeGroups)],
            'edu_keywords' => 'array',
        ];
    }

    public function scopeEducational($query)
    {
        return $query->whereJsonLength('edu_media_types', '>', 0);
    }

    public function category()
    {
        return $this->belongsTo(\App\Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return substr($string, 0, strrpos($string, ' ')) . ' ...';
    }

    public function getTitleColorAttribute($value)
    {
        return !empty($value) ? $value : '#fff';
    }

    public function getTitleShadowAttribute($value)
    {
        return !empty($value) ? $value : '#777';
    }

    public function scopePublished($query)
    {
        return $query->where('publish', '=', 1);
    }

    public function getContentImages()
    {
        return array_merge(parseUrls($this->summary), parseUrls($this->content));
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

        $this->attributes['publish'] = (bool) $value;
    }

    public function getReadingTimeAttribute()
    {
        return getEstimatedReadingTime($this->summary . ' ' . $this->content, \App::getLocale());
    }

    public function getParsedContentAttribute()
    {
        return Str::of(html_entity_decode($this->content))->replaceMatches(
            '/\[teaser.*?\]/',
            function ($match) {
                $teaser = Teaser::buildFromMarkup($match[0]);
                if (empty($teaser)) {
                    return '';
                }
                return $teaser->render();
            }
        );
    }
}
