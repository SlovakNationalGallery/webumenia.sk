<?php
use Fadion\Bouncy\BouncyTrait;

class Authority extends Eloquent {

	// use BouncyTrait;

	protected $table = 'authorities';

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
		'death_place',
		'death_date',
	);

	protected $guarded = array();

	public static $rules = array(
		'name' => 'required',
		);

	public $incrementing = false;


	// ELASTIC SEARCH INDEX
	/*
	public static function boot()
	{
	    parent::boot();

	    static::created(function($item)
	    {
	        $client = new Elasticsearch\Client();
	        $item->index();
	    });

	    static::updated(function($item)
	    {
	        $client = new Elasticsearch\Client();
	        $item->index();

	    });

		static::deleting(function($item) {
			$image = $item->getImagePath(true); // fullpath, disable no image
			if ($image) {
				@unlink($image); 
			}
			$item->collections()->detach();
        });	    

	    static::deleted(function($item)
	    {
	        $client = new Elasticsearch\Client();
	        $client->delete([
	        	'index' => Config::get('app.elasticsearch.index'),
	        	'type' => self::ES_TYPE,
	        	'id' => $item->id,
        	]);
	    });
	}
	*/

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

    public function links()
    {
        return $this->morphMany('Link', 'linkable');
    }    


	public  function index() {
		/*
	        $client = new Elasticsearch\Client();
	        $data = [
	        	'identifier' => $this->attributes['identifier'],
	        	'title' => $this->attributes['title'],
	        	'author' => $this->makeArray($this->attributes['author']),
				'description' => (!empty($this->attributes['description'])) ? strip_tags($this->attributes['description']) : '',	        	
				'work_type' => $this->work_types,
	        	'topic' => $this->attributes['topic'],
	        	'subject' => $this->makeArray($this->attributes['subject']),
	        	'place' => $this->makeArray($this->attributes['place']),
	        	'measurement' => $this->measurments,
	        	'date_earliest' => $this->attributes['date_earliest'],
	        	'date_latest' => $this->attributes['date_latest'],
	        	'medium' => $this->attributes['medium'],
	        	'technique' => $this->makeArray($this->attributes['technique']),
	        	'gallery' => $this->attributes['gallery'],
	        	'created_at' => $this->attributes['created_at'],
	        	'free_download' => $this->attributes['free_download'],
	        ];
	        return $client->index([
	        	'index' => Config::get('app.elasticsearch.index'),
	        	'type' =>  self::ES_TYPE,
	        	'id' => $this->attributes['id'],
	        	'body' =>$data,
        	]);		
        	*/
	}

}