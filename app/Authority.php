<?php

namespace App;

use App\Contracts\IndexableModel;
use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;

class Authority extends Model implements IndexableModel, TranslatableContract
{
    use Translatable;
    use HasBelongsToManyEvents;

    protected $table = 'authorities';

    const ARTWORKS_DIR = '/images/autori/';

    public $translatedAttributes = [
        'type_organization',
        'biography',
        'roles',
        'birth_place',
        'death_place'
    ];

    protected $fillable = array(
        'id',
        'type',
        'type_organization',
        'name',
        'sex',
        'biography',
        'birth_place',
        'birth_date',
        'birth_year',
        'death_place',
        'death_date',
        'death_year',
        'image_source_url',
        'image_source_label',
        'roles',
    );

    protected $dates = array(
        'created_at',
        'updated_at',
    );

    protected $with = array('nationalities', 'names');

    protected $guarded = array();

    protected $observables = [
        'belongsToManyAttaching',
        'belongsToManyAttached',
        'belongsToManyDetaching',
        'belongsToManyDetached',
        'belongsToManySyncing',
        'belongsToManySynced',
        'belongsToManyToggling',
        'belongsToManyToggled',
        'belongsToManyUpdatingExistingPivot',
        'belongsToManyUpdatedExistingPivot',
    ];

    public static $rules = array(
        'name' => 'required',
    );

    public $incrementing = false;

    public function nationalities()
    {
        return $this->belongsToMany(\App\Nationality::class)->withPivot('prefered');
    }

    public function names()
    {
        return $this->hasMany(\App\AuthorityName::class);
    }

    public function events()
    {
        return $this->hasMany(\App\AuthorityEvent::class);
    }

    public function relationships()
    {
        return $this->belongsToMany(\App\Authority::class, 'authority_relationships', 'authority_id', 'related_authority_id')->withPivot('type');
    }

    public function items()
    {
        return $this->belongsToMany(\App\Item::class);
    }

    public function record()
    {
        return $this->hasOne(\App\SpiceHarvesterRecord::class, 'item_id');
    }

    public function links()
    {
        return $this->morphMany(\App\Link::class, 'linkable');
    }

    public function getCollectionsCountAttribute()
    {
        if (!Cache::has('authority_collections_count')) {
            $authority_collections_count = $this->join('authority_item', 'authority_item.authority_id', '=', 'authorities.id')->join('collection_item', 'collection_item.item_id', '=', 'authority_item.item_id')->where('authorities.id', '=', $this->id)->select('collection_item.collection_id')->distinct()->count();
            Cache::put('authority_collections_count', $authority_collections_count, 3600);
        }

        return Cache::get('authority_collections_count');
    }

    public function getTagsAttribute()
    {
        if (!Cache::has('authority_tags')) {
            $tags = $this->join('authority_item', 'authority_item.authority_id', '=', 'authorities.id')
                            ->join('tagging_tagged', function ($join) {
                                $join->on('tagging_tagged.taggable_id', '=', 'authority_item.item_id');
                                $join->on('tagging_tagged.taggable_type', '=', DB::raw("'Item'"));
                            })->where('authorities.id', '=', $this->id)->groupBy('tagging_tagged.tag_name')->select('tagging_tagged.tag_name', DB::raw('count(tagging_tagged.tag_name) as pocet'))->orderBy('pocet', 'desc')->limit(10)->get();
            $authority_tags = $tags->pluck('tag_name');
            Cache::put('authority_tags', $authority_tags, 3600);
        }

        return Cache::get('authority_tags');
    }

    public function getFormatedNameAttribute()
    {
        return formatName($this->name);
    }

    public function getTitleAttribute()
    {
        return formatName($this->name);
    }

    public function getFormatedNamesAttribute()
    {
        $names = $this->names->pluck('name');
        $return_names = array();
        foreach ($names as $name) {
            $return_names[] = formatName($name);
        }

        return $return_names;
    }

    public function getPlacesAttribute($html = false)
    {
        $places = array_merge([
            $this->birth_place,
            $this->death_place,
            ], $this->events->pluck('place')->all());

        return array_values(array_filter(array_unique($places)));
    }

    public function getImagePath($full = false, $resize = false)
    {
        return self::getImagePathForId($this->id, $this->has_image, $this->sex, $full, $resize);
        // : self::ARTWORKS_DIR . "no-image.jpg";;
    }

    public function removeImage()
    {
        $dir = dirname($this->getImagePath(true));

        return File::cleanDirectory($dir);
    }

    public function getUrl()
    {
        return self::detailUrl($this->id);
    }

    public function getOaiUrl()
    {
        return Config::get('app.old_url').'/oai-pmh-new/authority?verb=GetRecord&metadataPrefix=ulan&identifier='.$this->id;
    }

    public static function detailUrl($authority_id)
    {
        return URL::to('autor/'.$authority_id);
    }

    public function getDescription($html = false, $links = false, $include_roles = false)
    {
        $description = ($html) ? '* ' : '';
        $description .= ($html) ? addMicrodata($this->birth_date, 'birthDate') : $this->birth_year;
        $description .= ($html) ? self::formatPlace($this->birth_place, $links, 'birthPlace') : self::formatPlace($this->birth_place, $links);
        if ($this->death_year) {
            $description .= ($html) ? ' &ndash; ' : ' - ';
            $description .= ($html) ? '&#x271D; ' : '';
            $description .= ($html) ? addMicrodata($this->death_date, 'deathDate') : $this->death_year;
            $description .= ($html) ? self::formatPlace($this->death_place, $links, 'deathPlace') : self::formatPlace($this->death_place, $links);
        }
        if ($include_roles) {
            $roles = $this->roles;
            if ($roles) {
                $description .= '. Role: '.implode(', ', $roles);
            }
        }

        return $description;
    }

    private static function formatPlace($place, $links = false, $itemprop = null)
    {
        if (empty($place)) {
            return '';
        } else {
            if ($links) {
                $prop = ($itemprop) ? 'itemprop="'.$itemprop.'"' : '';
                $place = '<a href="'.url_to('autori', ['place' => $place]).'" '.$prop.'>'.$place.'</a>';
            }

            return ' '.$place;
            // return add_brackets($place);
        }
    }

    public static function getImagePathForId($id, $has_image, $sex = 'male', $full = false, $resize = false)
    {
        if (!$has_image && !$full) {
            return self::getNoImage($sex);
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

    private static function getNoImage($sex = 'male')
    {
        $filename = 'no-image-'.$sex.'.jpeg';

        return self::ARTWORKS_DIR.$filename;
    }

    public function getIndexedData($locale)
    {
        if ($this->type !== 'person') {
            throw new \RuntimeException();
        }

        $translation = $this->translateOrNew($locale);
        return [
            'id' => $this->id,
            'identifier' => $this->id,
            'name' => $this->name,
            'alternative_name' => $this->names()->pluck('name'),
            'related_name' => $this->relationships()->pluck('name'),
            'nationality' => $this->nationalities()->pluck('code'),
            'place' => $this->places,
            'role' => $this->roles,
            'birth_year' => $this->birth_year,
            'death_year' => $this->death_year,
            'sex' => $this->sex,
            'has_image' => (boolean) $this->has_image,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'items_count' => $this->items()->count(),
            'items_with_images_count' => $this->items()->hasImage()->count(),
            'biography' => (!empty($translation->biography)) ? strip_tags($translation->biography) : '',
            'birth_place' => $translation->birth_place,
            'death_place' => $translation->death_place,
        ];
    }

    /* pre atributy vo viacerych jazykoch
    napr. "štúdium/study" alebo "učiteľ/teacher" */
    public static function formatMultiAttribute($atttribute, $index = 0)
    {
        $atttribute = explode('/', $atttribute);

        if (\App::getLocale() == 'en') {
            $index = 1;
        }

        return (isSet($atttribute[$index])) ? $atttribute[$index] : null;
    }

    public function getAssociativeRelationships()
    {
        $associative_relationships = array();
        foreach ($this->relationships as $i => $relationship) {
            $associative_relationships[self::formatMultiAttribute($relationship->pivot->type)][] = [
                'id' => $relationship->id,
                'name' => formatName($relationship->name),
                ];
        }

        return $associative_relationships;
    }

    public function setBiographyAttribute($value)
    {
        $this->attributes['biography'] = ($value) ?: '';
    }

    public function incrementViewCount($save = true)
    {
        $this->timestamps = false;
        $this->view_count++;
        if ($save) {
            $this->save();
        }
    }
}
