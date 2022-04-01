<?php

namespace App;

use App\Concerns\DelegatesAttributes;
use App\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ShuffledItem extends Model implements HasMedia
{
    use DelegatesAttributes;
    use HasFactory;
    use InteractsWithMedia;
    use Publishable;

    protected $casts = [
        'crop' => 'array',
    ];

    protected $delegated = [
        'title' => 'item',
        'dating_formatted' => 'item',
    ];

    protected $fillable = ['is_published', 'item_id', 'crop'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getAuthorLinksAttribute()
    {
        return $this->item->authors_with_authorities->map(
            fn($a) => (object) [
                'label' => formatName($a->name),
                'url' => isset($a->authority)
                    ? $a->authority->getUrl()
                    : route('frontend.catalog.index', ['author' => $a->name]),
            ]
        );
    }

    public function getCropUrlAttribute()
    {
        $iipParams = [
            'FIF' => $this->item->images()->first()->iipimg_url,
            'WID' => 1600, // Max size
            'RGN' => join(',', [
                $this->crop['x'],
                $this->crop['y'],
                $this->crop['width'],
                $this->crop['height'],
            ]),
            'CVT' => 'jpeg',
        ];

        // Turn into FIF=abc&WID=def ...
        $urlParams = collect($iipParams)
            ->map(fn($value, $name) => join('=', [$name, $value]))
            ->values()
            ->join('&');

        return sprintf('%s/?%s', config('app.iip_private'), $urlParams);
    }

    public function getImageAttribute(): ?Media
    {
        return $this->getFirstMedia('image');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->withResponsiveImages();
    }
}
