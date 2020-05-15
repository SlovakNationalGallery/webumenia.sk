<?php

namespace App;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic;

class Collection extends Model implements TranslatableContract
{
    use Translatable;

    const ARTWORKS_DIR = '/images/kolekcie/';

    public $translatedAttributes = ['name','type', 'text'];

    public static $rules = array(
        'sk.name' => 'required',
        'sk.text' => 'required',
    );

    public static $sortable = array(
        'published_at' => 'sortable.published_at',
        'updated_at' => 'sortable.updated_at',
        'name'       => 'sortable.title',
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
                $img = \Image::make(public_path('images/no-image/no-image.jpg'))->fit($resize)->sharpen(7);
            }

            $img->save($full_path . "$file.$resize.jpg");
        }
        $result_path = self::ARTWORKS_DIR .  "$file.$resize.jpg";
        return $result_path;
    }

    public function getContentImages()
    {
        return parseUrls($this->text);
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<', Carbon::now());
    }

    public function getTitleColorAttribute($value)
    {
        return (!empty($value)) ? $value : '#fff';
    }

    public function getTitleShadowAttribute($value)
    {
        return (!empty($value)) ? $value : '#777';
    }

    public function scopeOrderByTranslation(Builder $query, $column, $dir = 'asc', $locale = null)
    {
        $locale = $locale ?: $this->locale();

        return $query
            ->join('collection_translations as t', function ($join) use ($locale) {
                $join->on('collections.id', '=', 't.collection_id')
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

            $withTranslation = self::whereHas('translations', function (Builder $q) use ($locale, $alias) {
                $q->where($this->getLocaleKey(), '=', $locale);
            })->pluck('id')->toArray();

            if ($withTranslation) {
                $query->whereNotIn("$alias.collection_id", $withTranslation);
            }
        });
    }
}
