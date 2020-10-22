<?php

namespace App;

use App\Traits\HasLinks;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Space extends Model implements HasMediaConversions
{
    use \Conner\Tagging\Taggable;
    use Translatable;
    use HasMediaTrait;
    use HasLinks;


    protected $table = 'spaces';

    const ARTWORKS_DIR = '/images/priestory/';

    public $translatedAttributes = [
        'name',
        'address',
        'opened_place',
        'description',
        'bibliography',
        'exhibitions',
        'archive',
    ];

    public static $filterable = array(
        'miesto' => 'opened_place',
        'tagy' => 'tag',
    );

    public static $sortable = array(
        'name'                    => 'sortable.name',
        'opened_date'              => 'sortable.opened_date',
        'random'                  => 'sortable.random',
    );

    protected $fillable = array(
        'id',
        'opened_date',
        'closed_date',
        'name',
        'description',
        'address',
        'opened_place',
        'bibliography',
        'exhibitions',
        'archive',
    );

    protected $dates = array(
        'opened_date',
        'closed_date',
        'created_at',
        'updated_at',
    );

    protected $guarded = array();

    public static $rules = array();

    // ELASTIC SEARCH INDEX

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($space) {
            $space->removeImage();
        });

    }

    public function getImagePath($full = false)
    {
        return self::getImagePathForId($this->id, $this->has_image, $full);
    }

    public function removeImage()
    {
        $dir = dirname($this->getImagePath(true));

        return File::cleanDirectory($dir);
    }

    public function getUrl()
    {
        return URL::to('vystavne-priestory/'.$this->id);
    }


    public static function getImagePathForId($id, $has_image, $full = false, $resize = false)
    {
        if (!$has_image && !$full) {
            return self::getNoImage();
        }

        $levels = 1;
        $dirsPerLevel = 100;

        $transformedWorkArtID = hashcode((string) $id);
        $workArtIdInt = abs(intval32bits($transformedWorkArtID));
        $tmpValue = $workArtIdInt;
        $dirsInLevels = array();

        $galleryDir = substr($id, 4, 3);

        for ($i = 0; $i < $levels; ++$i) {
            $dirsInLevels[$i] = $tmpValue % $dirsPerLevel;
            $tmpValue = $tmpValue / $dirsPerLevel;
        }

        $path = implode('/', $dirsInLevels);

        // adresar obrazkov workartu sa bude volat presne ako id, kde je ':' nahradena '_'
        $trans = array(':' => '_', ' ' => '_');
        $file = strtr($id, $trans);

        $relative_path = self::ARTWORKS_DIR."$galleryDir/$path/$file/";
        $full_path = public_path().$relative_path;

        // ak priecinky este neexistuju - vytvor ich
        if ($full && !file_exists($full_path)) {
            mkdir($full_path, 0777, true);
        }

        // dd($full_path . "$file.jpeg");
        if ($full) {
            $result_path = $full_path."$file.jpeg";
        } else {
            if (file_exists($full_path."$file.jpeg")) {
                $result_path = $relative_path."$file.jpeg";

                if ($resize) {
                    if (!file_exists($full_path."$file.$resize.jpeg")) {
                        $img = \Image::make($full_path."$file.jpeg")->fit($resize)->sharpen(7);
                        $img->save($full_path."$file.$resize.jpeg");
                    }
                    $result_path = $relative_path."$file.$resize.jpeg";
                }
            } else {
                $result_path = self::ARTWORKS_DIR.'no-image.jpg';
            }
        }

        return $result_path;
    }

    private static function getNoImage()
    {
        $filename = 'no-image.jpeg';

        return self::ARTWORKS_DIR.$filename;
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ($value) ?: '';
    }

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb_s')
             ->setManipulations(['w' => 300])
             ->performOnCollections('*');

        $this->addMediaConversion('thumb_m')
             ->setManipulations(['w' => 600])
             ->performOnCollections('*');


        $this->addMediaConversion('thumb_l')
             ->setManipulations(['w' => 800])
             ->performOnCollections('*');
    }

    public function getDescription($html = false, $links = false, $include_roles = false)
    {
        $description = str_limit(strip_tags($this->description), 160); // 160 is max lenght for meta description https://moz.com/learn/seo/meta-description

        return $description;
    }
}
