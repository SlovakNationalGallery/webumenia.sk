<?php
use Fadion\Bouncy\BouncyTrait;

class Authority extends Eloquent {

	use BouncyTrait;

	protected $table = 'authorities';

    const ARTWORKS_DIR = '/images/autori/';
    const ES_TYPE = 'authorities';

	// protected $indexName = 'webumenia';
    protected $typeName = self::ES_TYPE;

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

	    static::created(function($item)
	    {
	        $item->index();
	    });

	    static::updated(function($item)
	    {
	        $item->index();

	    });

		static::deleting(function($item) {
			$image = $item->getImagePath(true); // fullpath, disable no image
			if ($image) {
				@unlink($image); 
			}
        });	    

	    static::deleted(function($item)
	    {
	        Elastic::delete([
	        	'index' => Config::get('app.elasticsearch.index'),
	        	'type' => self::ES_TYPE,
	        	'id' => $item->id,
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
        return $this->hasMany('AuthorityRelationship');
    }

	public function items()
    {
        return $this->belongsToMany('Item');
    }

    public function links()
    {
        return $this->morphMany('Link', 'linkable');
    }    

    public function getPlacesAttribute()
    {
        $places = array_merge([
        	$this->birth_place, 
        	$this->death_place
        	], $this->events->lists('place'));

        return array_values(array_filter(array_unique($places)));
    }    


	public function getImagePath($full=false) {
		
		return ($this->attributes['has_image'] || $full) ? self::getImagePathForId($this->id, $full) : self::ARTWORKS_DIR . "no-image.jpg";;

	}

	public function getDetailUrl() {
		return URL::to('autor/' . $this->id);
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

	public  function index() {
		
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
        	'has_image' => $this->has_image,
        	'created_at' => $this->attributes['created_at'],
        	'items_count' => $this->items->count(),
        ];
        return Elastic::index([
        	'index' => Config::get('app.elasticsearch.index'),
        	'type' =>  self::ES_TYPE,
        	'id' => $this->attributes['id'],
        	'body' =>$data,
    	]);     	
	}

}