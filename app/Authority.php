<?php
use Fadion\Bouncy\BouncyTrait;

class Authority extends Eloquent {

	use BouncyTrait;

	protected $table = 'authorities';

    const ARTWORKS_DIR = '/images/autori/';
    const ES_TYPE = 'authorities';

	// protected $indexName = 'webumenia';
    protected $typeName = self::ES_TYPE;

	public static $filterable = array(
		'rola' => 'role',
		'príslušnosť' => 'nationality',
		'miesto' => 'place',
	);

	public static $sortable = array(
		'name' => 'mena',
		'items_count' => 'počtu diel',
		'birth_year' => 'roku narodenia',
		'items_with_images_count' => 'počtu diel s obrázkom',
		'random' => 'náhodne'
		// 'created_at' => 'počtu diel',
	);

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
	);

	protected $with = array('roles', 'nationalities', 'names');

	protected $guarded = array();

	public static $rules = array(
		'name' => 'required',
		);

	public $incrementing = false;


	// ELASTIC SEARCH INDEX
	
	public static function boot()
	{
	    parent::boot();

	    static::created(function($authority)
	    {
	        $authority->index();
	        foreach ($authority->items as $item) {
	        	$item->index();
	        }
	    });

	    static::updated(function($authority)
	    {
	        $authority->index();

	    });

		static::deleting(function($authority) {

			$authority->removeImage();
			$authority->nationalities()->detach();
			$authority->relationships()->detach();
			$authority->items()->detach();
			$authority->roles()->delete();
			$authority->names()->delete();
			$authority->events()->delete();

        });	    

	    static::deleted(function($authority)
	    {
	        Elastic::delete([
	        	'index' => Config::get('fadion/bouncy::config.index'),
	        	'type' => self::ES_TYPE,
	        	'id' => $authority->id,
        	]);
	    });
	}
	

	public function nationalities()
    {
        return $this->belongsToMany('Nationality')->withPivot('prefered');
    }

	public function roles()
    {
        return $this->hasMany('AuthorityRole');
    }

	public function names()
    {
        return $this->hasMany('AuthorityName');
    }

	public function events()
    {
        return $this->hasMany('AuthorityEvent');
    }

	public function relationships()
    {
        return $this->belongsToMany('Authority', 'authority_relationships', 'authority_id', 'related_authority_id')->withPivot('type');;
    }

	public function items()
    {
        return $this->belongsToMany('Item');
    }

    public function record()
    {
    	return $this->hasOne('SpiceHarvesterRecord', 'item_id');
    }

	public function getPreviewItems()
    {
    	$params = array();
    	$params["size"] = 10;
    	$params["sort"][] = "_score";
    	$params["sort"][] = ["created_at"=>["order"=>"desc"]];
    	$params["query"] = [
    		"bool"=> [
    			"must" => [
    				["term"=> [ "authority_id" => $this->attributes['id']]]
    			],
    			"should" => [
    				["term"=> [ "has_image" => true ]],
    				["term"=> [ "has_iip" => true ]]
    			]
    		]
    	];
    	return Item::search($params);
        // return $this->belongsToMany('Item')->where('publish', '=', 1)->where('publish', '=', 1)->orderBy('has_image', 'desc')->orderBy('view_count', 'desc')->limit(10)->remember(5);
    }

    public function links()
    {
        return $this->morphMany('Link', 'linkable');
    }

    public function getCollectionsCountAttribute()
    {
	    $collections = $this->join('authority_item', 'authority_item.authority_id', '=', 'authorities.id')->join('collection_item', 'collection_item.item_id', '=', 'authority_item.item_id')->where('authorities.id', '=', $this->id)->select('collection_item.collection_id')->distinct()->remember(30)->get();
	    return $collections->count();
    }

    public function getTagsAttribute()
    {
	    // return true;
	    $tags = $this->join('authority_item', 'authority_item.authority_id', '=', 'authorities.id')
	    					->join('tagging_tagged', function($join) {
						    	$join->on('tagging_tagged.taggable_id', '=', 'authority_item.item_id');
						    	$join->on('tagging_tagged.taggable_type', '=', DB::raw("'Item'"));
						    })->where('authorities.id', '=', $this->id)->groupBy('tagging_tagged.tag_name')->select('tagging_tagged.tag_name', DB::raw('count(tagging_tagged.tag_name) as pocet'))->orderBy('pocet', 'desc')->limit(10)->remember(30)->get();
	    return $tags->lists('tag_name');
    }

	public function getFormatedNameAttribute()
    {
        return self::formatName($this->name);
    }

	public function getTitleAttribute()
    {
        return self::formatName($this->name);
    }

    public function getFormatedNamesAttribute()
    {
    	$names = $this->names->lists('name');
		$return_names = array();
		foreach ($names as $name) {
			$return_names[] = self::formatName($name);
		}
		return $return_names;
    }

    public function getPlacesAttribute($html = false)
    {
        $places = array_merge([
        	$this->birth_place, 
        	$this->death_place
        	], $this->events->lists('place'));

        return array_values(array_filter(array_unique($places)));
    }    


	public function getImagePath($full=false) {
		
		return self::getImagePathForId($this->id, $this->attributes['has_image'], $this->attributes['sex'], $full);
		// : self::ARTWORKS_DIR . "no-image.jpg";;

	}

	public function removeImage()
	{
		$dir = dirname( $this->getImagePath(true) ); 
		return File::cleanDirectory($dir);
	}

	public function getUrl() {
		return self::detailUrl($this->id);
	}

	public function getOaiUrl()
	{
		return Config::get('app.old_url').'/oai-pmh-new/authority?verb=GetRecord&metadataPrefix=ulan&identifier='.$this->id;
	}

	public static function detailUrl($authority_id) {
		return URL::to('autor/' . $authority_id);
	}

	public function getDescription($html = false, $links = false, $include_roles = false)
	{
		$description = ($html) ? '* ' : '';
		$description .= ($html) ? addMicrodata($this->birth_date,'birthDate') : $this->birth_year;
		$description .= ($html) ? self::formatPlace($this->birth_place, $links,'birthPlace') : self::formatPlace($this->birth_place, $links);
		if ($this->death_year) {
			$description .= ($html) ? ' &ndash; ' : ' - '; 
			$description .= ($html) ? '&#x271D; ' : '';
			$description .= ($html) ? addMicrodata($this->death_date,'deathDate') : $this->death_year;
			$description .= ($html) ? self::formatPlace($this->death_place, $links,'deathPlace') : self::formatPlace($this->death_place, $links);
		}
		if ($include_roles) {
			$roles = array();
			foreach ($this->roles as $i=>$role) $roles[] = $role->role;
			if ($roles) $description .= '. Role: ' . implode(', ', $roles);
			
		}
		return $description;
	}

	private static function formatPlace($place, $links = false, $itemprop = NULL)
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
		if (!$has_image && !$full) return self::getNoImage($sex);

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
	    	mkdir($full_path, 0777, true);
	    }

	    // dd($full_path . "$file.jpeg");
	    if ($full) {
	    	$result_path = $full_path . "$file.jpeg";
	    } else {
		    if (file_exists($full_path . "$file.jpeg")) {
		    	$result_path =  $relative_path . "$file.jpeg";

		    	if ($resize) {
		    		if (!file_exists($full_path . "$file.$resize.jpeg")) {
		    			$img = Image::make( $full_path . "$file.jpeg")->fit($resize)->sharpen(7);
		    			$img->save($full_path . "$file.$resize.jpeg");		    			
		    		}
		    		$result_path = $relative_path . "$file.$resize.jpeg";
		    	}
		    } else {
		    	$result_path =  self::ARTWORKS_DIR . "no-image.jpg";
		    }
	    }

		return $result_path;
	}

	private static function getNoImage($sex = 'male') {
		$filename = 'no-image-' . $sex . '.jpeg';
		return self::ARTWORKS_DIR . $filename;
	}

	public  function index() {
		if ($this->attributes['type'] != 'person') {
			return false;
		}
        $client = new Elasticsearch\Client();
        $data = [
        	'identifier' => $this->attributes['id'],
        	'name' => $this->attributes['name'],
        	'alternative_name' => $this->names->lists('name'),
        	'related_name' => $this->relationships->lists('name'),
			'biography' => (!empty($this->attributes['biography'])) ? strip_tags($this->attributes['biography']) : '',
			'nationality' => $this->nationalities->lists('code'),
        	'place' => $this->places,
        	'role' => $this->roles->lists('role'),
        	'birth_year' => $this->birth_year,
        	'death_year' => $this->death_year,
        	'birth_place' => $this->birth_place,
        	'death_place' => $this->death_place,
        	'sex' => $this->sex,
        	'has_image' => (boolean)$this->has_image,
        	'created_at' => $this->attributes['created_at'],
        	'items_count' => $this->items->count(),
        	'items_with_images_count' => $this->items()->hasImage()->count(),
        ];
        return Elastic::index([
        	'index' => Config::get('fadion/bouncy::config.index'),
        	'type' =>  self::ES_TYPE,
        	'id' => $this->attributes['id'],
        	'body' =>$data,
    	]);     	
	}

	public static function sliderMin() {
		$table_name = with(new static)->getTable();
		if (Cache::has($table_name.'.slider_min')) {
			$slider_min =  Cache::get($table_name.'.slider_min');
		}
		else {
			$min_year = self::min('birth_year');
			$slider_min = floor($min_year / 100)*100;
			Cache::put($table_name.'.slider_min', $slider_min, 60);
		}
		return $slider_min;
	}

	public static function sliderMax() {
		return date('Y');
	}

	public static function formatName($name) {
		return preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $name);
	}

	/* pre atributy vo viacerych jazykoch
	napr. "štúdium/study" alebo "učiteľ/teacher" */
	public static function formatMultiAttribute($atttribute, $index=0) {
		$atttribute = explode('/', $atttribute);
		return $atttribute[$index];
	}

	public static function listValues($attribute, $search_params)
	{
		//najskor over, ci $attribute je zo zoznamu povolenych 
		if (!in_array($attribute, self::$filterable)) return false;
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
	        	'search_type' => 'count',
	        	'type' => self::ES_TYPE,
	        	'body'  => $params       	
	      	]);
		$buckets = $result['aggregations'][$attribute]['buckets'];

		$return_list = array();
		foreach ($buckets as $bucket) {
			// dd($bucket);
			$single_value = $bucket['key'];
			// if ($attribute=='author') $single_value = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $single_value);
			$return_list[$bucket['key']] = "$single_value ({$bucket['doc_count']})";
		}
		return $return_list;

	}

	public function getAssociativeRelationships() {
		$associative_relationships = array();
		foreach ($this->relationships as $i=>$relationship) {
			$associative_relationships[self::formatMultiAttribute($relationship->pivot->type)][] = [
				'id' => $relationship->id,
				'name' => self::formatName($relationship->name)
				];
		}
		return $associative_relationships;
	}

	public static function amount($custom_parameters = []) {
		$params = array();
		foreach ($custom_parameters as $attribute => $value) {
			$params["query"]["filtered"]["filter"]["and"][]["term"][$attribute] = $value;
		}
		$authorities = self::search($params);
		return $authorities->total();
	}

}