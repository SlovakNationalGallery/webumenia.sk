<?php

namespace App;

use App\Concerns\HasHeaderImage;
use App\Concerns\Publishable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class Collection extends Model implements TranslatableContract
{
    use HasHeaderImage;
    use Publishable;
    use Translatable;
    use HasFactory;

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('home.collections'));
    }

    function getArtworksDirAttribute()
    {
        return '/images/kolekcie/';
    }

    public $translatedAttributes = ['name', 'type', 'text'];

    public static $rules = [
        'sk.name' => 'required',
        'sk.text' => 'required',
    ];

    public static $sortable = [
        'published_at' => 'sortable.published_at',
        'updated_at' => 'sortable.updated_at',
        'name' => 'sortable.title',
    ];

    protected $dates = ['created_at', 'updated_at', 'published_at'];

    public function items()
    {
        return $this->belongsToMany(\App\Item::class, 'collection_item', 'collection_id', 'item_id')
            ->withPivot('order')
            ->orderBy('order', 'asc');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
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
        return $striped_string > $string
            ? substr($string, 0, strrpos($string, ' ')) . ' ...'
            : $string;
    }

    public function getContentImages()
    {
        return parseUrls($this->text);
    }

    public function getTitleColorAttribute($value)
    {
        return !empty($value) ? $value : '#fff';
    }

    public function getTitleShadowAttribute($value)
    {
        return !empty($value) ? $value : '#777';
    }

    public function scopeOrderByTranslation(Builder $query, $column, $dir = 'asc', $locale = null)
    {
        $locale = $locale ?: $this->locale();

        return $query
            ->join('collection_translations as t', function ($join) use ($locale) {
                $join
                    ->on('collections.id', '=', 't.collection_id')
                    ->where(function ($query) use ($locale) {
                        $query->where('t.locale', '=', $locale);

                        if ($this->useFallback()) {
                            $this->joinFallbackLocale($query, $locale);
                        }
                    });
            })
            ->select(['collections.*', 't.name', 't.type', 't.text'])
            ->orderBy("t.$column", $dir);
    }

    protected function joinFallbackLocale(JoinClause $query, $locale = null, $alias = 't')
    {
        $locale = $locale ?: $this->locale();

        return $query->orWhere(function ($query) use ($locale, $alias) {
            $query->where("$alias.locale", '=', $this->getFallbackLocale());

            $withTranslation = self::whereHas('translations', function (Builder $q) use (
                $locale,
                $alias
            ) {
                $q->where($this->getLocaleKey(), '=', $locale);
            })
                ->pluck('id')
                ->toArray();

            if ($withTranslation) {
                $query->whereNotIn("$alias.collection_id", $withTranslation);
            }
        });
    }

    public function getReadingTimeAttribute()
    {
        return getEstimatedReadingTime($this->text, \App::getLocale());
    }
}
