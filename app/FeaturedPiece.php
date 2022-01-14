<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FeaturedPiece extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'excerpt',
        'url',
        'publish',
        'type',
    ];

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

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('image')
            ->singleFile()
            ->withResponsiveImages();
    }
}
