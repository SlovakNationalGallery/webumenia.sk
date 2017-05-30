<?php



namespace App;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\Cache;
use Fadion\Bouncy\Facades\Elastic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Fadion\Bouncy\BouncyTrait;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    use BouncyTrait;
    use \Conner\Tagging\Taggable;

    const ARTWORKS_DIR = '/images/diela/';
    const ES_TYPE = 'items';

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
        'lokalita' => 'place',
        'len s obrázkom' => 'has_image',
        'len so zoom' => 'has_iip',
        'len voľné' => 'is_free',
        'zo súboru' => 'related_work'
    );

    public static $sortable = array(
        'updated_at' => 'poslednej zmeny',
        'created_at' => 'dátumu pridania',
        'title' => 'názvu',
        'author' => 'autora',
        'date_earliest' => 'datovania',
        'view_count' => 'počtu videní',
        'random' => 'náhodne'
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
        'img_url',
        'iipimg_url',
        'item_type',
        'publish',
    );

    public static $rules = array(
        'author' => 'required',
        'title' => 'required',
        'dating' => 'required',
        );

    // protected $appends = array('measurements');

    public $incrementing = false;

    protected $guarded = array('featured');

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

    // ELASTIC SEARCH INDEX
    public static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            $item->index();
        });

        static::updated(function ($item) {
            $item->index();
        });

        static::deleting(function ($item) {
            $item->removeImage();
            $item->collections()->detach();
        });

        static::deleted(function ($item) {
             $this->getElasticClient()->delete([
                // 'index' => Config::get('bouncy.index'),
                'type' => self::ES_TYPE,
                'id' => $item->id,
             ]);
        });
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

    public function getImagePath($full = false)
    {
        return self::getImagePathForId($this->id, $full);

    }

    public function removeImage()
    {
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

    public function downloadImage()
    {
        $file = $this->img_url;
        if (empty($file)) {
            return false;
        }

        $data = file_get_contents($file);

        $full = true;
        if ($new_file = $this->getImagePath($full)) {
            file_put_contents($new_file, $data);
            return true;
        }

        return false;
    }

    public function getOaiUrl()
    {
        return Config::get('app.old_url').'/oai-pmh/?verb=GetRecord&metadataPrefix=oai_dc&identifier='.$this->id;
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
                        "ids" => [$this->attributes['id']],
                        "min_term_freq" => 1,
                        "minimum_should_match" => 3,
                        "min_word_length" => 3,
                        ]
                    ]
                ],
                "should" => [
                    // ["match"=> [
                    // 	"author" => $this->attributes['author'],
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

        if (Config::get('request.domain') == 'mg') {
            $params['query']['bool']['must'][1]['term']['gallery'] = "Moravská galerie, MG";
        }

        return self::search($params);
    }

    public static function getImagePathForId($id, $full = false, $resize = false)
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
            if (file_exists($full_path . "$file.jpeg")) {
                $result_path =  $relative_path . "$file.jpeg";

                if ($resize) {
                    if (!file_exists($full_path . "$file.$resize.jpeg")) {
                        $img = \Image::make($full_path . "$file.jpeg")->fit($resize)->sharpen(7);
                        $img->save($full_path . "$file.$resize.jpeg");
                    }
                    $result_path = $relative_path . "$file.$resize.jpeg";
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
        $authors_array = $this->makeArray($this->attributes['author']);
        $authors = array();
        foreach ($authors_array as $author) {
            $authors[$author] = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $author);
        }
        return $authors;
    }

    public function getAuthorFormated($value)
    {
        return preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $this->attributes['author']);
    }

    public function getFirstAuthorAttribute($value)
    {
        $authors_array = $this->makeArray($this->attributes['author']);
        return reset($authors_array);
    }

    public function getSubjectsAttribute($value)
    {
        $subjects_array = $this->makeArray($this->attributes['subject']);
        return $subjects_array;
    }

    public function getTopicsAttribute($value)
    {
        return $this->makeArray($this->attributes['topic']);
    }

    public function getMediumsAttribute($value)
    {
        return $this->makeArray($this->attributes['medium']);
    }

    public function getTechniquesAttribute($value)
    {
        return $this->makeArray($this->attributes['technique']);
    }

    public function getMeasurementsAttribute($value)
    {
        $trans = array("; " => ";", "()" => "");
        return explode(';', strtr($this->attributes['measurement'], $trans));

        // $measurements_array = explode(';', $this->attributes['measurement']);
        // $measurements = array();
        // $measurements[0] = array();
        // $i = -1;
        // if (!empty($this->attributes['measurement'])) {
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
        $measurements =  explode(';', strtr($this->attributes['measurement'], $trans));
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
        $formated = strtr($formated, $trans);
        return $formated;
    }

    public function getWorkTypesAttribute()
    {
        return (explode(', ', $this->attributes['work_type']));
    }

    public function setMediumAttribute($value)
    {
        $this->attributes['medium'] = $value ?: '';
    }

    public function setTechniqueAttribute($value)
    {
        $this->attributes['technique'] = $value ?: '';
    }

    public function setLat($value)
    {
        $this->attributes['lat'] = $value ?: null;
    }

    public function setLng($value)
    {
        $this->attributes['lng'] = $value ?: null;
    }

    public function makeArray($str)
    {
        if (is_array($str)) {
            return $str;
        }
        $str = trim($str);
        return (empty($str)) ? array() : explode('; ', $str);
    }

    public static function listValues($attribute, $search_params)
    {
        //najskor over, ci $attribute je zo zoznamu povolenych
        if (!in_array($attribute, self::$filterable)) {
            return false;
        }
        $json_params = '
		{
		 "aggs" : { 
		    "'.$attribute.'" : {
		        "terms" : {
		          "field" : "'.$attribute.'",
		          "size": 1000
		        }
		    }
		}
		}
		';
        $params = array_merge(json_decode($json_params, true), $search_params);
        $result = Elastic::search([
                // 'index' => Config::get('bouncy.index'),
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
     * ci je dielo volne
     * autor min 70 rokov po smrti - alebo je autor neznamy
     */
    public function isFree()
    {
        $copyright_length = 70; // 70 rokov po smrti autora
        $limit_according_item_dating = $copyright_length + 60; // 60 = 80 (max_life_lenght) - 20 (start_of_publishing)
        
        // skontrolovat, ci dielo patri institucii, ktora povoluje "volne diela"
        if (!(
            $this->attributes['gallery'] == 'Slovenská národná galéria, SNG' ||
            $this->attributes['gallery'] == 'Oravská galéria, OGD' ||
            $this->attributes['gallery'] == 'Liptovská galéria Petra Michala Bohúňa, GPB' ||
            $this->attributes['gallery'] == 'Galéria umenia Ernesta Zmetáka, GNZ' ||
            $this->attributes['gallery'] == 'Galéria Miloša Alexandra Bazovského, GBT'
        )) {
            return false;
        }
        
        //ak je autor viac ako 71rokov po smrti
        $authors_are_free = array();
        foreach ($this->authorities as $i => $authority) {
            $authors_are_free[$i] = false;
            if (!empty($authority->death_year)) {
                // $death = cedvuDatetime($authority->death_year);
                // $years = $death->diffInYears(Carbon::now());
                $years = date('Y') - $authority->death_year; // podla zakona sa rata volne dielo, ak je autor viac adko 70 rokov po smrti - od zaciatku nasledujuceho roka (1.1.) - co osetruje tento lame vypocet
                if ($years > $copyright_length) {
                    $authors_are_free[$i] = true;
                }
            }
        }
        if (!empty($authors_are_free)) {
            if (count(array_unique($authors_are_free)) === 1 && end($authors_are_free) == true) {
                return true;
            } else {
                return false;
            }
        }

        //ak je autor neznamy
        if (stripos($this->attributes['author'], 'neznámy') !== false) {
            return true;
        }

        //ak je dielo naozaj stare
        if ((date('Y') - $this->attributes['date_latest']) > $limit_according_item_dating) {
            return true;
        }

        return false;
    }
    
    private function relatedAuthorityIds()
    {
        $ids=array();
        foreach ($this->authorities as $authority) {
            $ids[] = $authority->id;
        }
        return $ids;
    }

    public function isFreeDownload()
    {
        return ($this->isFree() && !empty($this->attributes['iipimg_url']));
    }

    public function isForReproduction()
    {
        return ($this->attributes['gallery'] == 'Slovenská národná galéria, SNG');
    }

    public function scopeHasImage($query)
    {
        return $query->where('has_image', '=', 1);
    }

    public function scopeHasZoom($query)
    {
        return $query->where('iipimg_url', 'NOT LIKE', '');
    }

    public function scopeForReproduction($query)
    {
        return $query->where('gallery', '=', 'Slovenská národná galéria, SNG');
    }

    public function download()
    {

        header('Set-Cookie: fileDownload=true; path=/');
        $url = 'http://imi.sng.cust.eea.sk/publicIS/fcgi-bin/iipsrv.fcgi?FIF=' . $this->attributes['iipimg_url'] . '&CVT=JPG';
        $filename = $this->attributes['id'].'.jpg';

        set_time_limit(0);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $r = curl_exec($ch);
        curl_close($ch);
        header('Expires: 0'); // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
        header('Cache-Control: private', false);
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($r)); // provide file size
        header('Connection: close');
        echo $r;

        // Finish off, like Laravel would
        // Event::fire('laravel.done', array($response));
        // $response->foundation->finish();

        exit;
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
        $authors_all = DB::table('authority_item')->where('item_id', $this->attributes['id'])->get();
        foreach ($authors_all as $author) {
            if (!in_array($author->name, $used_authorities) && !empty($author->name)) {
                $link = '<a class="underline" href="'. url_to('katalog', ['author' => $author->name]) .'">'. preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $author->name) .'</a>';
                if ($author->role != 'autor/author') {
                    $link .= ' &ndash; ' . Authority::formatMultiAttribute($author->role);
                }
                $authorities_with_link[] = $link;
                $used_authorities[]= trim($author->name, ', ');
            }
        }
        foreach ($this->authors as $author_unformated => $author) {
            if (!in_array($author_unformated, $used_authorities)) {
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

    public function index()
    {
            $client =  $this->getElasticClient();
            $work_types = $this->work_types;
            $main_work_type = reset($work_types);
            $data = [
                'id' => $this->attributes['id'],
                'identifier' => $this->attributes['identifier'],
                'title' => $this->attributes['title'],
                'author' => $this->makeArray($this->attributes['author']),
                'description' => (!empty($this->attributes['description'])) ? strip_tags($this->attributes['description']) : '',
                'work_type' => $main_work_type, // ulozit iba prvu hodnotu
                'topic' => $this->makeArray($this->attributes['topic']),
                'tag' => $this->tagNames(),
                'place' => $this->makeArray($this->attributes['place']),
                'measurement' => $this->measurments,
                'dating' => $this->dating,
                'date_earliest' => $this->attributes['date_earliest'],
                'date_latest' => $this->attributes['date_latest'],
                'medium' => $this->attributes['medium'],
                'technique' => $this->makeArray($this->attributes['technique']),
                'gallery' => $this->attributes['gallery'],
                'updated_at' => $this->attributes['updated_at'],
                'created_at' => $this->attributes['created_at'],
                'has_image' => (bool)$this->has_image,
                'has_iip' => (bool)$this->iipimg_url,
                'is_free' => $this->isFree(),
                // 'free_download' => $this->isFreeDownload(), // staci zapnut is_free + has_iip
                'related_work' => $this->related_work,
                'authority_id' => $this->relatedAuthorityIds(),
                'view_count' => $this->view_count,
            ];

            return $client->index([
                'index' => Config::get('bouncy.index'),
                'type' =>  self::ES_TYPE,
                'id' => $this->attributes['id'],
                'body' =>$data,
            ]);
    }

    public static function getSortedLabel($sort_by = null)
    {
        if ($sort_by==null) {
            $sort_by = Input::get('sort_by');
        }

        if (array_key_exists($sort_by, self::$sortable)) {
            $sort_by = Input::get('sort_by');
        } else {
            $sort_by = "updated_at";
        }

        $label = self::$sortable[$sort_by];

        if (Input::has('search') && head(self::$sortable)==$label) {
            return 'relevancie';
        }

        return $label;

    }

    public static function random($size = 1, $custom_parameters = [])
    {
        $params = array();
        $random = json_decode('
			{"_script": {
			    "script": "Math.random() * 200000",
                "lang" : "groovy",
			    "type": "number",
			    "params": {},
			    "order": "asc"
			 }}', true);
        $params["sort"][] = $random;
        $params["query"]["filtered"]["filter"]["and"][]["term"]["has_image"] = true;
        $params["query"]["filtered"]["filter"]["and"][]["term"]["has_iip"] = true;
        foreach ($custom_parameters as $attribute => $value) {
            $params["query"]["filtered"]["filter"]["and"][]["term"][$attribute] = $value;
        }
        $params["size"] = $size;

        if (Config::get('request.domain') == 'mg') {
            $params['query']['filtered']['filter']["and"][]["term"]["gallery"] = "Moravská galerie, MG";
        }

        return self::search($params);
    }

    public static function amount($custom_parameters = [])
    {
        $params = array();
        foreach ($custom_parameters as $attribute => $value) {
            $params["query"]["filtered"]["filter"]["and"][]["term"][$attribute] = $value;
        }
        $items = self::search($params);
        return $items->total();
    }
}
