<?php



namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{

    const ARTWORKS_DIR = '/images/intro/';

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

    public function getImagePathAttribute()
    {
        return asset(self::ARTWORKS_DIR . '/' . $this->id . '/' . $this->image . '.jpg');
    }

    public function getImageSrcsetAttribute()
    {   
        return 
        asset(self::ARTWORKS_DIR . '/' . $this->id . '/' . $this->image . '.jpg'). ' 400w, ' .
        asset(self::ARTWORKS_DIR . '/' . $this->id . '/' . $this->image . '.800.jpg'). ' 800w, ' .
        asset(self::ARTWORKS_DIR . '/' . $this->id . '/' . $this->image . '.1200.jpg'). ' 1200w, ' .
        asset(self::ARTWORKS_DIR . '/' . $this->id . '/' . $this->image . '.original.jpg'). ' 1201w' ;
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
