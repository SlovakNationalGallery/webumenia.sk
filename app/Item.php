<?php



namespace App;

use App\Contracts\IndexableModel;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Events\ItemPrimaryImageChanged;
use App\Matchers\AuthorityMatcher;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use ElasticScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Constraint;
use Intervention\Image\Image;
use Primal\Color\Parser;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Item extends Model implements IndexableModel, TranslatableContract
{
    use \Conner\Tagging\Taggable;
    use HasBelongsToManyEvents;
    use HasFactory;
    use Searchable;
    use Translatable;

    const ARTWORKS_DIR = '/images/diela/';

    const COPYRIGHT_LENGTH = 70;
    const GUESSED_AUTHORISM_TIMESPAN = 60;
    const FREE_NEVER = PHP_INT_MAX;
    const UNKNOWN_AUTHOR_THRESHOLD = 1940;
    const TREE_DELIMITER = '/';

    const COLOR_AMOUNT_THRESHOLD = 0.03;

    protected $keyType = 'string';

    public $translatedAttributes = [
        'title',
        'description',
        'description_source',
        'description_source_link',
        'work_type',
        'object_type',
        'work_level',
        'topic',
        'subject',
        'measurement',
        'dating',
        'medium',
        'technique',
        'inscription',
        'place',
        'state_edition',
        'gallery',
        'credit',
        'relationship_type',
        'related_work',
        'additionals',
        'style_period',
        'current_location',
    ];

    protected $fillable = [
        'id',
        'identifier',
        'author',
        'title',
        'description',
        'description_user_id',
        'description_source',
        'description_source_link',
        'work_type',
        'work_level',
        'topic',
        'subject',
        'measurement',
        'dating',
        'date_earliest',
        'date_latest',
        'medium',
        'technique',
        'inscription',
        'place',
        'lat',
        'lng',
        'state_edition',
        'relationship_type',
        'related_work',
        'related_work_order',
        'related_work_total',
        'gallery',
        'publish',
        'contributor',
        'acquisition_date',
        'additionals',
        'style_period',
        'current_location',
        'exhibition',
        'box',
        'location',
    ];

    public static $rules = array(
        'author' => 'required',
        'date_earliest' => 'required',
        'date_latest' => 'required',
        'sk.title'  => 'required',
        'sk.dating' => 'required',
    );

    public $incrementing = false;

    protected $casts = [
        'colors' => 'json',
        'frontends' => 'array',
    ];

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

    protected $appends = ['image_url'];

    protected $useTranslationFallback;

    public function getCitation()
    {
        return $this->getTitleWithAuthors() . ', ' . $this->getDatingFormated() . ', ' . $this->gallery . ', ' . $this->getUrl();
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addGetterConstraint('images', new Valid());
    }

    public function descriptionUser()
    {
        return $this->belongsTo(\App\User::class, 'description_user_id');
    }

    public function authorities()
    {
        return $this->belongsToMany(
            \App\Authority::class,
            'authority_item',
            'item_id',
            'authority_id'
        )->withPivot(['role', 'automatically_matched']);
    }

    public function collections()
    {
        return $this->belongsToMany(\App\Collection::class, 'collection_item', 'item_id', 'collection_id');
    }

    public function getPublishedCollectionsAttribute()
    {
        return $this->collections()->published();
    }

    public function record()
    {
        return $this->hasOne(\App\SpiceHarvesterRecord::class, 'item_id');
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class)
            ->orderBy('order_column')
            ->orderBy('iipimg_url');
    }

    public function getImages() {
        return $this->images;
    }

    public function addImage(ItemImage $image) {
        $image->item_id = $this->id;
        $this->images->add($image);
    }

    public function removeImage(ItemImage $image) {
        $index = $this->images->search($image);
        if ($index !== false) {
            $this->images->forget($index);
        }
        $image->delete();
    }

    public function getImagePath($full = false, $resize = false, $resize_method = 'fit')
    {
        return self::getImagePathForId($this->id, $full, $resize, $resize_method);
    }

    public function getImageModificationDateTime()
    {
        if (!$this->hasImageForId($this->id)) {
            return null;
        }

        $path = $this->getImagePath(true);
        return Date::createFromTimestamp(filemtime($path));
    }

    public function deleteImage() {
        $this->colors = null;
        $this->save();

        $dir = dirname($this->getImagePath(true));
        return File::cleanDirectory($dir);
    }

    public function getUrl($params = [])
    {
        $url = URL::to('dielo/' . $this->id);
        if ($params) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

    public function getOaiUrlAttribute(): ?string
    {
        return $this->record?->url;
    }

    /**
     * $resize_methods = fit | widen | heighten
     */
    public static function getImagePathForId($id, $full = false, $resize = false, $resize_method = 'fit')
    {

        $levels = 1;
        $dirsPerLevel = 100;

        $transformedWorkArtID = hashcode((string)$id);
        $workArtIdInt = abs(intval32bits($transformedWorkArtID));
        $tmpValue = $workArtIdInt;
        $dirsInLevels = array();

        $galleryDir = substr($id, 4, 3);

        for ($i = 0; $i < $levels; $i++) {
                $dirsInLevels[$i] = $tmpValue % $dirsPerLevel;
                $tmpValue = $tmpValue / $dirsPerLevel;
        }

        $path = implode("/", $dirsInLevels);

        // adresar obrazkov workartu sa bude volat presne ako id, kde je ':' nahradena '_'
        $trans = array(":" => "_", " " => "_");
        $file = strtr($id, $trans);

        $relative_path = self::ARTWORKS_DIR . "$galleryDir/$path/$file/";
        $full_path =  public_path() . $relative_path;

        // ak priecinky este neexistuju - vytvor ich
        if ($full && !file_exists($full_path)) {
            mkdir($full_path, 0775, true);
        }

        // dd($full_path . "$file.jpeg");
        if ($full) {
            $result_path = $full_path . "$file.jpeg";
        } else {
            // file exist && is valid JPEG file
            if (file_exists($full_path . "$file.jpeg") && (@exif_imagetype($full_path . "$file.jpeg")==IMAGETYPE_JPEG)) {
                $result_path =  $relative_path . "$file.jpeg";

                if ($resize) {
                    $method_prefix = ($resize_method == 'fit') ? '' : substr($resize_method, 0, 1);
                    if (!file_exists($full_path . "$file.$resize$method_prefix.jpeg")) {
                        $img = \Image::make($full_path . "$file.jpeg");
                        switch ($resize_method) {
                            case 'widen':
                                $img->widen($resize, function ($constraint) {
                                    $constraint->upsize();
                                });
                                break;

                            case 'heighten':
                                $img->heighten($resize, function ($constraint) {
                                    $constraint->upsize();
                                });
                                break;

                            default:
                                $img->fit($resize, $resize, function ($constraint) {
                                    $constraint->upsize();
                                });
                                break;
                        }
                        $img->sharpen(5);
                        $img->save($full_path . "$file.$resize$method_prefix.jpeg");
                    }
                    $result_path = $relative_path . "$file.$resize$method_prefix.jpeg";
                }
            } else {
                $result_path =  self::getNoImage($id);
            }
        }

        return $result_path;
    }

    public static function hasImageForId($id)
    {
        $image = self::getImagePathForId($id);
        return !str_contains($image, 'no-image');
    }

    public static function getNoImage($id)
    {
        $allowed_work_types = array(
            'g', //grafika
            'k', //kresba
            'o', //obraz
            'p', //plastika / socha
            'im', //ine media
            'up-dk', //fotografia
            'up-p', //graficky dizaj
            'up-f', //uzitkove umenie
            'up-t', //sperk
        );
        if (preg_match('~\.(.*?)_~', $id, $work_type)) {
            $work_type = mb_strtolower($work_type[1], "UTF-8");
            if (in_array($work_type, $allowed_work_types)) {
                return "/images/no-image/diela/no-image-{$work_type}.jpg";
            }
        }
        return "/images/no-image/diela/no-image.jpg";
    }

    public function getAuthorsAttribute($value)
    {
        $authors_array = $this->makeArray($this->author);
        $authors = array();
        foreach ($authors_array as $author) {
            $authors[$author] = formatName($author);
        }

        return $authors;
    }

    public function getAuthorsFormattedAttribute($value){
        return array_map( function($a){return formatName($a);}, $this->authors) ;
    }

    public function getAuthorsWithoutAuthority()
    {
        return app(AuthorityMatcher::class)
            ->matchAll($this, $onlyExisting = true)
            ->filter(function (\Illuminate\Support\Collection $authorities) {
                return $authorities->isEmpty();
            })
            ->keys();
    }

    public function getAuthorsWithAuthoritiesAttribute()
    {
        return app(AuthorityMatcher::class)
            ->matchAll($this, onlyExisting: true)
            ->map(function (EloquentCollection $authorities, string $author) {
                $authority = $authorities->first();
                return (object) [
                    'name' => $authority->name ?? $author,
                    'authority' => $authority,
                ];
            })
            ->values();
    }

    public function getUniqueAuthorsWithAuthorityNames()
    {
        return $this->authors_with_authorities
            ->pluck('name')
            ->filter()
            ->values();
    }

    public function getFirstAuthorAttribute($value)
    {
        $authors_array = $this->makeArray($this->author);
        return reset($authors_array);
    }

    public function getSubjectsAttribute($value)
    {
        $subjects_array = $this->makeArray($this->subject);
        return $subjects_array;
    }

    public function getTopicsAttribute($value)
    {
        return $this->makeArray($this->topic);
    }

    public function getMediumsAttribute($value)
    {
        return $this->makeArray($this->medium);
    }

    public function getTechniquesAttribute($value)
    {
        return $this->makeArray($this->technique);
    }

    public function getMeasurementsAttribute($value)
    {
        return self::formatMeasurement($this->measurement);
    }

    public static function formatMeasurement(?string $measurement): array
    {
        $trans = ['; ' => ';', '()' => ''];
        return explode(';', strtr($measurement, $trans));
    }

    public function getWidthAttribute($value)
    {
        return $this->getMeasurementForDimension('šírka');
    }

    public function getHeightAttribute($value)
    {
        return $this->getMeasurementForDimension('výška');
    }

    private function getMeasurementForDimension($dimension)
    {
        $value = null;
        $trans = array("; " => ";", ", " => ";", "()" => "");
        $measurements =  explode(';', strtr($this->measurement, $trans));
        foreach ($measurements as $measurement) {
            if (str_contains($measurement, $dimension)) {
                $value = preg_replace("/[^0-9\.]/", "", $measurement);
            }
        }
        return $value;
    }

    public function getDatingFormated($multi_line = false)
    {
        $count_digits = preg_match_all("/[0-9]/", $this->dating);
        if (($count_digits<2) && !empty($this->date_earliest)) {
            $formated = $this->date_earliest;
            if (!empty($this->date_latest) && $this->date_latest!=$this->date_earliest) {
                $formated .= '–' . $this->date_latest;
            }
            return $formated;
        }
        $trans = [
            "/" => "–",
            "-" => "–",
        ];
        if ($multi_line) {
           $trans["; "] = "\n";
        }
        $formated = preg_replace('/^([0-9]*) \s*([a-zA-Z]*)$/', '$2 $1', $this->dating);
        $parts = explode('/', $formated);
        $formated = implode('/', array_unique($parts));
        $formated = strtr($formated, $trans);
        return $formated;
    }

    public function getWorkTypeTreeAttribute()
    {
        return $this->unserializeTrees($this->work_type);
    }

    public function getObjectTypeTreeAttribute()
    {
        return $this->unserializeTrees($this->object_type);
    }

    protected function unserializeTrees($serializedTrees)
    {
        $trees = $this->makeArray($serializedTrees);
        return array_map(function ($tree) {
            $stack = [];
            return array_map(function ($part) use (&$stack) {
                $stack[] = $part;
                return [
                    'name' => $part,
                    'path' => implode(self::TREE_DELIMITER, $stack)
                ];
            }, explode(', ', $tree));
        }, $trees);
    }

    public function setLat($value)
    {
        $this->attributes['lat'] = $value ?: null;
    }

    public function setLng($value)
    {
        $this->attributes['lng'] = $value ?: null;
    }

    public function makeArray($str, $delimiter = ';')
    {
        if (is_array($str)) {
            return $str;
        }

        $array = explode($delimiter, $str ?? '');
        $array = array_map(function ($value) {
            return trim($value);
        }, $array);
        return array_filter($array, function ($value) {
            return $value !== "";
        });
    }

    /**
     * @return bool
     */
    public function isFree() {
        return $this->freeFrom() <= time();
    }

    /**
     * @return int
     */
    public function freeFrom() {
        $default_locale = config('translatable.fallback_locale');
        if (!in_array($this["gallery:$default_locale"], [
            'Slovenská národná galéria, SNG',
            'Oravská galéria, OGD',
            'Liptovská galéria Petra Michala Bohúňa, GPB',
            'Galéria umenia Ernesta Zmetáka, GNZ',
            'Galéria Miloša Alexandra Bazovského, GBT',
            'Galéria umelcov Spiša, GUS',
            'Východoslovenská galéria, VSG',
        ])) {
            return self::FREE_NEVER;
        }

        $freeFromYear = $this->date_latest + self::GUESSED_AUTHORISM_TIMESPAN + self::COPYRIGHT_LENGTH + 1;

        $copyrightExpirationYears = [];
        foreach ($this->authorities as $authority) {
            if (!empty($authority->death_year)) {
                $copyrightExpirationYears[] = $authority->death_year + self::COPYRIGHT_LENGTH + 1;
            }
        }

        $yearToTimestamp = function ($year) {
            return (new \DateTime())
                ->setDate($year, 1, 1)
                ->setTime(0, 0)
                ->getTimestamp();
        };

        if ($copyrightExpirationYears) {
            $freeFromYear = min($freeFromYear, max($copyrightExpirationYears));
            return $yearToTimestamp($freeFromYear);
        }

        if ($this->isAuthorUnknown()) {
            return self::UNKNOWN_AUTHOR_THRESHOLD;
        }

        return $yearToTimestamp($freeFromYear);
    }

    public function isAuthorUnknown() {
        return stripos($this->author, 'neznámy') !== false;
    }

    public function isForReproduction()
    {
        $default_translation = $this->translate(config('translatable.fallback_locale'));

        if (is_null($default_translation)) return false;
        if ($default_translation->credit == 'Dar združenia Čierne diery') return false;
        if ($default_translation->gallery == 'Slovenská národná galéria, SNG') return true;

        return false;
    }

    public function scopeHasImage($query, $hasImage = true)
    {
        return $query->where('has_image', '=', $hasImage);
    }

    public function related()
    {
        $relatedIds = Item::search()
            ->where('related_work', $this->related_work)
            ->whereIn('author', $this->makeArray($this->author))
            ->take(1000) // Scout limits to 10 by default
            ->keys();

        return self::query()
            ->whereIn('id', $relatedIds)
            ->orderBy('related_work_order');
    }

    public function getTitleWithAuthors($html = false)
    {
        return implode(', ', $this->authors)  . ' – ' .  $this->title;
    }

    public function getHasIipAttribute($value) {
        if ($value !== null) {
            return $value;
        }

        return !$this->images->isEmpty();
    }

    public function getColors()
    {
        return collect($this->colors)->filter(function ($amount) {
            return $amount >= self::COLOR_AMOUNT_THRESHOLD;
        });
    }

    public function getHasColorsAttribute() {
        return !$this->getColors()->isEmpty();
    }

    /**
     * @param mixed $file
     */
    public function saveImage($file) {
        $path = $this->getImagePath($full = true);

        /** @var Image $image */
        $image = \Image::make($file);
        if ($image->width() > $image->height()) {
            $image->widen(800, function (Constraint $constraint) {
                $constraint->upsize();
            });
        } else {
            $image->heighten(800, function (Constraint $constraint) {
                $constraint->upsize();
            });
        }

        $this->deleteImage();
        $image->save($path);

        $this->has_image = true;
        $this->image_ratio = $image->getWidth() / $image->getHeight();
        $this->save();

        event(new ItemPrimaryImageChanged($this));

        return $image;
    }

    public function getIndexedData($locale)
    {
        $formatTree = function ($serializedTrees) {
            $unserialized = $this->unserializeTrees($serializedTrees);
            return array_map(function ($tree) {
                return end($tree)['path'];
            }, $unserialized);
        };

        return [
            'id' => $this->id,
            'identifier' => $this->identifier,
            'author' => $this->getUniqueAuthorsWithAuthorityNames(),
            'authors' => $this->authors_with_authorities->map(
                fn($a) => [
                    'name' => $a->name,
                    'authority' => $a->authority ? $a->authority->only(['id']) : null,
                ]
            ),
            'tag' => $this->tagNames(), // @TODO translate model
            'date_earliest' => $this->date_earliest,
            'date_latest' => $this->date_latest,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'has_image' => (bool)$this->has_image,
            'has_iip' => $this->has_iip,
            'has_text' => (bool)$this["description:$locale"],
            'is_free' => $this->isFree(),
            'is_for_reproduction' => $this->isForReproduction(),
            'authority_id' => $this->authorities->pluck('id'),
            'view_count' => $this->view_count,
            'work_type' => $formatTree($this["work_type:$locale"]),
            'object_type' => $formatTree($this["object_type:$locale"]),
            'image_ratio' => $this->image_ratio,
            'title' => $this["title:$locale"],
            'description' => (!empty($this["description:$locale"])) ? strip_tags($this["description:$locale"]) : '',
            'topic' => $this->makeArray($this["topic:$locale"]),
            'place' => $this->makeArray($this["place:$locale"]),
            'measurement' => self::formatMeasurement($this["measurement:$locale"]),
            'dating' => $this["dating:$locale"],
            'medium' => $this->makeArray($this["medium:$locale"]),
            'technique' => $this->makeArray($this["technique:$locale"]),
            'gallery' => $this["gallery:$locale"],
            'credit' => $this["credit:$locale"],
            'contributor' => $this->contributor,
            'related_work' => $this["related_work:$locale"],
            'exhibition' => $this->exhibition,
            'box' => $this->box,
            'location' => $this->location,
            'additionals' => $this["additionals:$locale"],
            'images' => $this->images
                ->map(function(ItemImage $image) {
                    return $image->iipimg_url;
                }),
            'hsl' => $this->getColors()
                ->map(function (float $amount, string $color) {
                    $hsl = Parser::Parse($color)->toHSL();
                    return [
                        'h' => $hsl->hue,
                        's' => $hsl->saturation,
                        'l' => $hsl->luminance,
                        'amount' => $amount,
                    ];
                })
                ->values(),
            'frontend' => $this->frontends,
        ];
    }

    public function getUseTranslationFallback()
    {
        return $this->useTranslationFallback;
    }

    public function setUseTranslationFallback(?bool $useTranslationFallback)
    {
        $this->useTranslationFallback = $useTranslationFallback;
    }

    public function searchableAs()
    {
        return app(ItemRepository::class)->getLocalizedIndexName();
    }

    public function getImageUrlAttribute()
    {
        return sprintf('%s%s', config('app.url'), $this->getImagePath());
    }

    public function syncMatchedAuthorities(\Illuminate\Support\Collection|array $idsWithPivotData)
    {
        $idsWithPivotData = collect($idsWithPivotData);
        // Detach automatically-matched authorities that no longer match
        $this->authorities()
            ->wherePivot('automatically_matched', true)
            ->whereNotIn('id', $idsWithPivotData->keys())
            ->detach();

        // Update existing pivot data
        foreach ($idsWithPivotData as $id => $pivotData) {
            $this->authorities()->updateExistingPivot($id, $pivotData);
        }

        // Create new authorities
        $existingIds = $this->authorities()->pluck('id');
        $newIds = $idsWithPivotData->keys()->diff($existingIds);
        $this->authorities()
            ->withPivotValue('automatically_matched', true)
            ->attach($idsWithPivotData->only($newIds));
    }
}
