<?php



namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class Slide extends HeaderImageModel
{

    const ARTWORKS_DIR = '/images/intro/';
    const IMAGE_PROPERTY='image';

    protected $fillable = [
                'title',
                'subtitle',
                'url',
                'publish',
    ];

    public static $rules = [
        'title' => 'required',
        'url' => 'required',
        'publish' => 'boolean',
        'image' => 'image|dimensions:min_width=1200',
    ];

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            $item->deleteImage();
        });
    }

    public function scopePublished($query)
    {
        return $query->where('publish', '=', 1);
    }
}
