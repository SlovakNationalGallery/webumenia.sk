<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FeaturedPiece extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('home.featured-piece'));
    }

    protected $fillable = ['title', 'excerpt', 'url', 'publish', 'type'];

    public static $rules = [
        'title' => 'required',
        'url' => 'required',
        'publish' => 'boolean',
        'image' => 'image|dimensions:min_width=1200',
        'type' => 'required|in:article,collection',
    ];

    public function scopePublished($query)
    {
        return $query->where('publish', '=', 1);
    }

    public function getImageAttribute(): ?Media
    {
        return $this->getFirstMedia('image');
    }

    public function getIsCollectionAttribute(): bool
    {
        return $this->type === 'collection';
    }

    public function getIsArticleAttribute(): bool
    {
        return $this->type === 'article';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->withResponsiveImages();
    }
}
