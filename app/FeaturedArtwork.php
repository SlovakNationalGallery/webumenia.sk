<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class FeaturedArtwork extends Model
{
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('home.featured-artwork'));
    }

    protected $fillable = ['is_published', 'item', 'item_id', 'title', 'description'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getIsPublishedAttribute()
    {
        return !!$this->published_at;
    }

    public function setIsPublishedAttribute(bool $isPublished)
    {
        if ($this->is_published === $isPublished) {
            return;
        }
        if (!$isPublished) {
            return $this->attributes['published_at'] = null;
        }

        $this->attributes['published_at'] = Carbon::now();
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', Carbon::now());
    }

    public function getAuthorLinksAttribute()
    {
        if (!$this->item) {
            return;
        }

        return $this->item->authors_with_authorities->map(
            fn($a) => (object) [
                'label' => formatName($a->name),
                'url' => isset($a->authority)
                    ? $a->authority->getUrl()
                    : route('frontend.catalog.index', ['author' => $a->name]),
            ]
        );
    }

    public function getMetadataLinksAttribute()
    {
        if (!$this->item) {
            return;
        }

        $dating = (object) [
            'label' => $this->item->getDatingFormated(),
            'url' => null,
        ];

        $techniques = collect($this->item->techniques)->map(
            fn($t) => (object) [
                'label' => $t,
                'url' => route('frontend.catalog.index', ['technique' => $t]),
            ]
        );

        $media = collect($this->item->mediums)->map(
            fn($m) => (object) [
                'label' => $m,
                'url' => route('frontend.catalog.index', ['medium' => $m]),
            ]
        );

        return collect([$dating])
            ->concat($techniques)
            ->concat($media);
    }
}
