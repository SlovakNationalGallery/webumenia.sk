<?php



namespace App;

use App\Events\ItemPrimaryImageChanged;
use Elasticsearch\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Constraint;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\Cache;
use Fadion\Bouncy\Facades\Elastic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Fadion\Bouncy\BouncyTrait;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Item extends Model
{
    use \Conner\Tagging\Taggable;
    use \Dimsav\Translatable\Translatable, BouncyTrait {
        \Dimsav\Translatable\Translatable::save insteadof BouncyTrait;
    }

    const ARTWORKS_DIR = '/images/diela/';
    const ES_TYPE = 'items';

    const COPYRIGHT_LENGTH = 70;
    const GUESSED_AUTHORISM_TIMESPAN = 60;
    const FREE_ALWAYS = 0;
    const FREE_NEVER = PHP_INT_MAX;

    public $translatedAttributes = [
        'title',
        'description',
        'description_source',
        'description_source_link',
        'work_type',
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
        'relationship_type',
        'related_work'
    ];

    // protected $indexName = 'webumenia';
    protected $typeName = self::ES_TYPE;

    public static $filterable = array(
        'autor' => 'author',
        'výtvarný druh' => 'work_type',
        'tagy' => 'tag',
        'galéria' => 'gallery',
        'žáner' => 'topic',
        'materiál' => 'medium',
        'technika' => 'technique',
        'len s obrázkom' => 'has_image',
        'len so zoom' => 'has_iip',
        'len voľné' => 'is_free',
        'zo súboru' => 'related_work'
    );

    public static $sortable;

    protected static $sortables = array(
        'relevance'     => 'sortable.relevance',
        'updated_at'    => 'sortable.updated_at',
        'created_at'    => 'sortable.created_at',
        'title'         => 'sortable.title',
        'author'        => 'sortable.author',
        'newest'        => 'sortable.newest',
        'oldest'        => 'sortable.oldest',
        'view_count'    => 'sortable.view_count',
        'random'        => 'sortable.random',
    );

    protected $fillable = array(
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
    );

    protected $dates = array(
        'created_at',
        'updated_at',
    );

    public static $rules = array(
        'author' => 'required',
        'date_earliest' => 'required',
        'date_latest' => 'required',
        'sk.title'  => 'required',
        'sk.dating' => 'required',
    );

    // protected $appends = array('measurements');

    public $incrementing = false;

    protected $mappingProperties = array(
        'title' => [
          'type' => 'string',
          "analyzer" => "standard",
        ],
        'author' => [
          'type' => 'string',
          "analyzer" => "standard",
        ],
    );

    protected $casts = array(
        'color_descriptor' => 'json',
    );

    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addGetterConstraint('images', new Valid());
    }

    // ELASTIC SEARCH INDEX
    public static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            $item->fresh()->index();
        });

        static::updated(function ($item) {
            $item->fresh()->index();
        });

        static::deleting(function ($item) {
            $item->deleteImage();
            $item->collections()->detach();
        });

        static::deleted(function ($item) {

            $elastic_translatable = \App::make('ElasticTranslatableService');

            foreach (config('translatable.locales') as $locale) {
                $item->getElasticClient()->delete([
                   'index' => $elastic_translatable->getIndexForLocale($locale),
                   'type' => $item::ES_TYPE,
                   'id' => $item->id,
                ]);
            }
        });
    }

    public static function getSortables() {
        return static::$sortables;
    }

    public function descriptionUser()
    {
        return $this->belongsTo(\App\User::class, 'description_user_id');
    }

    public function authorities()
    {
        return $this->belongsToMany(\App\Authority::class, 'authority_item', 'item_id', 'authority_id')->withPivot('role');
    }

    public function collections()
    {
        return $this->belongsToMany(\App\Collection::class, 'collection_item', 'item_id', 'collection_id');
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
        return $this->hasMany(ItemImage::class)->orderBy('order');
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

    public function getImagePath($full = false)
    {
        return self::getImagePathForId($this->id, $full);

    }

    public function deleteImage() {
        $this->color_descriptor = null;
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

    public function getOaiUrl()
    {
        return Config::get('app.old_url').'/oai-pmh/?verb=GetRecord&metadataPrefix=oai_dc&identifier='.$this->id;
    }

    public function similarByColor($size = 10)
    {
        if (!$this->color_descriptor) {
            throw new \RuntimeException;
        }

        $params = [
            'size' => $size,
            'sort' => [
                '_score' => 'desc'
            ],
            'query' => [
                'descriptor' => [
                    'color_descriptor' => [
                        'hash' => 'LSH',
                        'descriptor' => $this->color_descriptor
                    ]
                ]
            ]
        ];

        return self::search($params);
    }

    public function moreLikeThis($size = 10)
    {
        $params = array();
        $params["size"] = $size;
        // $params["sort"][] = "_score";
        // $params["sort"][] = "has_image";
        $params["query"] = [
            "bool"=> [
                "must" => [
                    ["more_like_this"=> [
                        "fields" => [
                            "author.folded","title","title.stemmed","description.stemmed", "tag.folded", "place", "technique"
                        ],
                        "ids" => [$this->id],
                        "min_term_freq" => 1,
                        "minimum_should_match" => 3,
                        "min_word_length" => 3,
                        ]
                    ]
                ],
                "should" => [
                    // ["match"=> [
                    // 	"author" => $this->author,
                    // 	],
                    // ],
                    // ["terms"=> [ "authority_id" => $this->relatedAuthorityIds() ] ],
                    ["term"=> [ "has_image" => [
                            "value" => true,
                            "boost" => 10
                            ]
                    ] ],
                    ["term"=> [ "has_iip" => true ]]
                ]
            ]
        ];
        return self::search($params);
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
                return self::ARTWORKS_DIR . "no-image-{$work_type}.jpg";
            }
        }
        return self::ARTWORKS_DIR . "no-image.jpg";
    }


    /*
	public function getAuthorAttribute($value)
	{
		$authors = $this->authors;
		return implode(', ', $authors);
	}
	*/

    public static function sliderMin()
    {
        $table_name = with(new static)->getTable();
        if (Cache::has($table_name.'.slider_min')) {
            $slider_min =  Cache::get($table_name.'.slider_min');
        } else {
            $min_year = self::min('date_earliest');
            $slider_min = floor($min_year / 100)*100;
            Cache::put($table_name.'.slider_min', $slider_min, 60);
        }
        return $slider_min;
    }

    public static function sliderMax()
    {
        return date('Y');
    }

    public function getAuthorsAttribute($value)
    {
        $authors_array = $this->makeArray($this->author);
        $authors = array();
        foreach ($authors_array as $author) {
            $authors[$author] = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $author);
        }
        return $authors;
    }

    public function getAuthorFormated($value)
    {
        return formatName($this->attributes['author']);
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
        $trans = array("; " => ";", "()" => "");
        return explode(';', strtr($this->measurement, $trans));

        // $measurements_array = explode(';', $this->measurement);
        // $measurements = array();
        // $measurements[0] = array();
        // $i = -1;
        // if (!empty($this->measurement)) {
        // 	foreach ($measurements_array as $key=>$measurement) {
        // 		if ($key%2 == 0) {
        // 			$i++;
        // 			$measurements[$i] = array();
        // 		}
        //      if (!empty($measurement)) {
        // 			$measurement = explode(' ', $measurement, 2);
        // 			if (isSet($measurement[1])) {
        // 				$measurements[$i][$measurement[0]] = $measurement[1];
        // 			} else {
        // 				$measurements[$i][] = $measurement[0];
        // 			}

        // 		}
        // 	}
        // }
        // return $measurements;
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

    public function getDatingFormated()
    {
        $count_digits = preg_match_all("/[0-9]/", $this->dating);
        if (($count_digits<2) && !empty($this->date_earliest)) {
            $formated = $this->date_earliest;
            if (!empty($this->date_latest) && $this->date_latest!=$this->date_earliest) {
                $formated .= "&ndash;" . $this->date_latest;
            }
            return $formated;
        }
        $trans = array("/" => "&ndash;", "-" => "&ndash;");
        $formated = preg_replace('/^([0-9]*) \s*([a-zA-Z]*)$/', '$2 $1', $this->dating);
        $parts = explode('/', $formated);
        $formated = implode('/', array_unique($parts));
        $formated = strtr($formated, $trans);
        return $formated;
    }

    public function getWorkTypesAttribute()
    {
        return $this->makeArray($this->work_type, ', ');
    }

    public function setLat($value)
    {
        $this->attributes['lat'] = $value ?: null;
    }

    public function setLng($value)
    {
        $this->attributes['lng'] = $value ?: null;
    }

    public function makeArray($str, $delimiter = '; ')
    {
        if (is_array($str)) {
            return $str;
        }
        $str = trim($str);
        return (empty($str)) ? array() : explode($delimiter, $str);
    }

    public static function listValues($attribute, $search_params)
    {
        if (!in_array($attribute, self::$filterable)) {
            return false;
        }

        $json_params = [
             'aggs' => [
                $attribute => [
                    'terms' => [
                        'field' => $attribute,
                        'size' => 1000,
                    ]
                ]
		    ]
		];

        $params = array_merge($json_params, $search_params);
        $result = Elastic::search([
            'index' => Config::get('bouncy.index'),
            'search_type' => 'count',
            'type' => self::ES_TYPE,
            'body'  => $params
        ]);
        $buckets = $result['aggregations'][$attribute]['buckets'];

        $return_list = array();
        foreach ($buckets as $bucket) {
            // dd($bucket);
            $single_value = $bucket['key'];
            if ($attribute=='author') {
                $single_value = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $single_value);
            }
            $return_list[$bucket['key']] = "$single_value ({$bucket['doc_count']})";
        }
        return $return_list;

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
        if (!in_array($this->translate($default_locale)->gallery, [
            'Slovenská národná galéria, SNG',
            'Oravská galéria, OGD',
            'Liptovská galéria Petra Michala Bohúňa, GPB',
            'Galéria umenia Ernesta Zmetáka, GNZ',
            'Galéria Miloša Alexandra Bazovského, GBT',
            'Galéria umelcov Spiša, GUS',
            'Východoslovenská galéria, VSG',
            'Památník národního písemnictví, PNP',
        ])) {
            return self::FREE_NEVER;
        }

        $freeFromYear = $this->date_latest + self::GUESSED_AUTHORISM_TIMESPAN + self::COPYRIGHT_LENGTH + 1;

        $copyrightExpirationYears = [];
        foreach ($this->authorities as $authority) {
            if (!empty($authority->death_year)) {
                $copyrightExpirationYears[] = $authority->death_year + self::COPYRIGHT_LENGTH + 1;
            } else {
                return self::FREE_NEVER;
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
            return self::FREE_ALWAYS;
        }

        return $yearToTimestamp($freeFromYear);
    }

    public function isAuthorUnknown() {
        return stripos($this->author, 'neznámy') !== false;
    }

    private function relatedAuthorityIds()
    {
        $ids=array();
        foreach ($this->authorities as $authority) {
            $ids[] = $authority->id;
        }
        return $ids;
    }

    public function isForReproduction()
    {
        $default_locale = config('translatable.fallback_locale');
        return ($this->translate($default_locale)->gallery == 'Slovenská národná galéria, SNG');
    }

    public function scopeHasImage($query, $hasImage = true)
    {
        return $query->where('has_image', '=', $hasImage);
    }

    public function scopeForReproduction($query)
    {
        $default_locale = config('translatable.fallback_locale');
        return $query->whereTranslation('gallery', 'Slovenská národná galéria, SNG', $default_locale);
    }


    public function scopeRelated($query, Item $item, $locale = null)
    {
        return $query->whereTranslation('related_work', $item->related_work, $locale)
            ->where('author', '=', $item->author)
            ->orderBy('related_work_order');
    }

    public function getAuthorsWithLinks()
    {
        $used_authorities = array();
        $authorities_with_link = array();
        $not_authorities_with_link = array();
        foreach ($this->authorities as $authority) {
            if ($authority->pivot->role != 'autor/author') {
                $not_authorities_with_link[] = '<a class="underline" href="'. $authority->getUrl() .'">'. $authority->formated_name .'</a>'
                    .' &ndash; ' . Authority::formatMultiAttribute($authority->pivot->role);
            } else {
                $authorities_with_link[] = '<span itemprop="creator" itemscope itemtype="http://schema.org/Person"><a class="underline" href="'. $authority->getUrl() .'" itemprop="sameAs"><span itemprop="name">'. $authority->formated_name .'</span></a></span>';
            }
            $used_authorities[]= trim($authority->name, ', ');
        }
        foreach ($this->authors as $author_unformated => $author) {
            if (!in_array(trim($author_unformated, ', '), $used_authorities)) {
                $authorities_with_link[] = '<a class="underline" href="'. url_to('katalog', ['author' => $author_unformated]) .'">'. $author .'</a>';
            }
        }

        return array_merge($authorities_with_link, $not_authorities_with_link);
    }

    public function getTitleWithAuthors($html = false)
    {
        $dash = ($html) ? ' &ndash; ' : ' - ';
        return implode(', ', $this->authors)  . $dash .  $this->title;
    }

    public function getHasIipAttribute($value) {
        if ($value !== null) {
            return $value;
        }

        return !$this->images->isEmpty();
    }

    public function index()
    {
        $client =  $this->getElasticClient();
        $elastic_translatable = \App::make('ElasticTranslatableService');

        foreach (config('translatable.locales') as $locale) {

            $item_translated = $this->translateOrNew($locale);

            $work_types = $this->makeArray($item_translated->work_type, ', ');
            $main_work_type = (is_array($work_types)) ? reset($work_types) : '';
            $data = [
                // non-tanslatable attributes:
                'id' => $this->id,
                'identifier' => $this->identifier,
                'author' => $this->makeArray($this->author),
                'tag' => $this->tagNames(), // @TODO translate this
                'date_earliest' => $this->date_earliest,
                'date_latest' => $this->date_latest,
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'has_image' => (bool)$this->has_image,
                'has_iip' => $this->has_iip,
                'is_free' => $this->isFree(),
                'authority_id' => $this->relatedAuthorityIds(),
                'view_count' => $this->view_count,
                'color_descriptor' => $this->color_descriptor,

                // tanslatable attributes:
                'work_type' => $main_work_type, // ulozit iba prvu hodnotu
                'title' => $item_translated->title,
                'description' => (!empty($item_translated->description)) ? strip_tags($item_translated->description) : '',
                'topic' => $this->makeArray($item_translated->topic),
                'place' => $this->makeArray($item_translated->place),
                'measurement' => $item_translated->measurments,
                'dating' => $item_translated->dating,
                'medium' => $item_translated->medium,
                'technique' => $this->makeArray($item_translated->technique),
                'gallery' => $item_translated->gallery,
                'related_work' => $item_translated->related_work,

            ];

            $client->index([
                'index' => $elastic_translatable->getIndexForLocale($locale),
                'type' =>  self::ES_TYPE,
                'id' => $this->id,
                'body' => $data,
            ]);
        }
    }

    public static function random($size = 1, $custom_parameters = [])
    {
        $custom_parameters['has_image'] = true;
        $custom_parameters['has_iip'] = true;

        $params = [];
        $params['query']['bool']['filter'] = static::getFilterParams($custom_parameters);
        $params['size'] = $size;
        $params['sort'] = [
            '_script' => [
                'script' => 'Math.random() * 200000',
                'type' => 'number',
                'order' => 'asc',
            ]
        ];

        return self::search($params);
    }

    public static function amount($custom_parameters = [])
    {
        $params = [];
        $params['query']['bool']['filter'] = static::getFilterParams($custom_parameters);
        $items = self::search($params);
        return $items->total();
    }

    public static function getFilterParams(array $attributes) {
        $filter = [];
        foreach ($attributes as $name => $value) {
            $filter['and'][]['term'][$name] = $value;
        }

        return $filter;
    }

    public function getColorsUsed($type = null) {
        $colors_used = [];

        for ($i = 0; $i < count($this->color_descriptor) / 4; $i++) {
            $amount_sqrt = $this->color_descriptor[4 * $i + 3];
            if (!$amount_sqrt) {
                continue;
            }
            $amount = $amount_sqrt * $amount_sqrt;
            $L = $this->color_descriptor[4 * $i];
            $a = $this->color_descriptor[4 * $i + 1];
            $b = $this->color_descriptor[4 * $i + 2];
            $color = new Color(['L' => $L, 'a' => $a, 'b' => $b], Color::TYPE_LAB);

            if ($type !== null) {
                $color = $color->convertTo($type);
            }

            $colors_used[$color->getValue()] = [
                'color' => $color,
                'amount' => $amount
            ];
        }

        return $colors_used;
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
        $this->save();

        event(new ItemPrimaryImageChanged($this));
    }

    protected function getElasticClient() {
        return app(Client::class);
    }
}
