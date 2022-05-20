<?php

namespace App;

use App\Concerns\Publishable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ShuffledItem extends Model implements HasMedia, TranslatableContract
{
    use Translatable;
    use HasFactory;
    use InteractsWithMedia;
    use Publishable;

    protected static function booted()
    {
        static::saved(function () {
            foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
                Cache::forget("home.shuffled-items.$locale");
            }
        });
    }

    public $translatedAttributes = ['filters'];

    protected $casts = [
        'crop' => 'array',
    ];

    protected $fillable = ['is_published', 'item_id', 'crop'];

    public function toShuffleOrchestratorItem()
    {
        return [
            'id' => $this->id,
            'authorLinks' => $this->author_links,
            'title' => $this->title,
            'url' => route('item.show', $this->item),
            'datingFormatted' => $this->dating_formatted,
            'filters' => collect($this->filters)->map(
                fn($f) => [
                    'url' => $f['url'],
                    'attributes' => collect($f['attributes'])->map(
                        fn($a) => [
                            'label' => trans('item.' . $a['name']),
                            'value' => $a['label'],
                        ]
                    ),
                ]
            ),
            'img' => [
                'src' => $this->getFirstMedia('image')->getUrl(),
                'srcset' => $this->getFirstMedia('image')->getSrcset(),
            ],
        ];
    }

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
            'WID' => 1920, // Max size
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

    public function getTitleAttribute()
    {
        return $this->item->title;
    }

    public function getDatingFormattedAttribute()
    {
        return $this->item->getDatingFormated();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->withResponsiveImages();
    }
}
