<?php

namespace App;

use App\Concerns\HasHeaderImage;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{

    use HasHeaderImage;

    function getArtworksDirAttribute()
    {
        return '/images/intro/';
    }

    function getImagePropertyAttribute()
    {
        return 'image';
    }

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

    public function getPath($create = false)
    {
        $folder_name = $this->id;
        $path = public_path() .  self::ARTWORKS_DIR . $folder_name . '/';
        if (!File::exists($path) && $create) {
            File::makeDirectory($path);
        }
        return $path;
    }

    public function removeImage()
    {
        $dir = $this->getPath();
        return File::cleanDirectory($dir);
    }
}
