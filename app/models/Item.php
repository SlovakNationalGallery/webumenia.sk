<?php
use Fadion\Bouncy\BouncyTrait;

class Item extends Eloquent {

	use BouncyTrait;

    const ARTWORKS_DIR = '/images/diela/';
    const ES_TYPE = 'items';

	// protected $indexName = 'webumenia';
    protected $typeName = self::ES_TYPE;

	protected $fillable = array(
		'id',
		'identifier',
		'author',
		'title',
		'description',
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
		'integrity',
		'integrity_work',
		'gallery',
		'img_url',
		'iipimg_url',
		'item_type',
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

	public function collections()
    {
        return $this->belongsToMany('Collection', 'collection_item', 'item_id', 'collection_id');
    }

	public function getImagePath($full=false) {

		$levels = 1;
	    $dirsPerLevel = 100;

	    $transformedWorkArtID = $this->hashcode((string)$this->id);
		$workArtIdInt = abs($this->intval32bits($transformedWorkArtID));
	    $tmpValue = $workArtIdInt;
	    $dirsInLevels = array();

	    $galleryDir = substr($this->id, 4, 3);

	    for ($i = 0; $i < $levels; $i++) {
	            $dirsInLevels[$i] = $tmpValue % $dirsPerLevel;
	            $tmpValue = $tmpValue / $dirsPerLevel;
	    }

	    $path = implode("/", $dirsInLevels);

		// adresar obrazkov workartu sa bude volat presne ako id, kde je ':' nahradena '_'
		$trans = array(":" => "_", " " => "_");
	    $file = strtr($this->id, $trans);

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
		    } else {
		    	$result_path =  self::ARTWORKS_DIR . "no-image.jpg";
		    }
	    }

		return $result_path;
	}

	public function getDetailUrl() {
		return URL::to('dielo/' . $this->id);
	}

	private function intval32bits($value)
	{
	    $value = ($value & 0xFFFFFFFF);

	    if ($value & 0x80000000)
	        $value = -((~$value & 0xFFFFFFFF) + 1);

	    return $value;
	}

	/*
	public function getAuthorAttribute($value)
	{
		$authors = $this->authors;
		return implode(', ', $authors);
	}
	*/

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

	public function getSubjectsAttribute($value)
	{
		$subjects_array = $this->makeArray($this->attributes['subject']);
		return $subjects_array;
	}

	public function getMeasurementsAttribute($value)
	{
		$measurements_array = explode(';', $this->attributes['measurement']);
		$measurements = array();
		$measurements[0] = array();
		$i = -1;
		if (!empty($this->attributes['measurement'])) {
			foreach ($measurements_array as $key=>$measurement) {
				if ($key%2 == 0) {
					$i++;
					$measurements[$i] = array();
				}
				if (!empty($measurement)) {				
					$measurement = explode(' ', $measurement, 2);
					$measurements[$i][$measurement[0]] = $measurement[1];
				}
			}			
		}
		return $measurements;
	}

	public function getDatingFormated() {

		$trans = array("/" => "&ndash;", "-" => "&ndash;", "okolo" => "");
		$formated = strtr($this->dating, $trans);
		return (str_contains($this->dating, 'okolo')) ? 'okolo ' . $formated : $formated;
	}

	public function getWorkTypesAttribute() {

		return (explode(', ', $this->attributes['work_type']));
	}


	/**
	* Same as java String.hashcode()
	*/
	private function hashcode($s) {
	    $len = strLen($s);
	    $sum = 0;
	    for ($i = 0; $i < $len; $i++) {
	        $char = ord($s[$i]);
	        $sum = (($sum<<5)-$sum)+$char;
	        $sum = $sum & 0xffffffff; // Convert to 32bit integer
	    }

	    return $sum;
	}

	public function setLat($value)
	{
	    $this->attributes['lat'] = $value ?: null;
	}

	public function setLng($value)
	{
	    $this->attributes['lng'] = $value ?: null;
	}

	public function makeArray($str) {
		return (is_array($str)) ? $str : explode('; ', $str);
	}

	public static function listValues($attribute, $delimiter = ';', $only_first = false)
	{
		//najskor over, ci $attribute je zo zoznamu povolenych 
		if (!in_array($attribute, array('author', 'work_type', 'subject', 'gallery'))) return false;

		$search = Input::get('search', null);
		$input = Input::all();

		$unformated_list = Item::select(DB::raw($attribute . ', count(*) AS pocet'))
		->groupBy($attribute)
		->orderBy('pocet', 'desc')
		->whereNotNull($attribute)
		->where($attribute, '!=', '')
		->where(function($query) use ($search, $input, $attribute) {
                /** @var $query Illuminate\Database\Query\Builder  */
                if (!empty($search)) {
                	$query->where('title', 'LIKE', '%'.$search.'%')->orWhere('author', 'LIKE', '%'.$search.'%')->orWhere('subject', 'LIKE', '%'.$search.'%')->orWhere('id', 'LIKE', '%'.$search.'%');
                }
                if(!empty($input['author']) && $attribute!='author') {
                	$query->where('author', 'LIKE', '%'.$input['author'].'%');
                }
                if(!empty($input['work_type']) && $attribute!='work_type') {
                	// dd($input['work_type']);
                	$query->where('work_type', 'LIKE', $input['work_type'].'%');
                }
                if(!empty($input['subject']) && $attribute!='subject') {
                	//tieto 2 query su tu kvoli situaciam, aby nenaslo pre kucove slovo napr. "les" aj diela s klucovy slovom "pleso"
                	$query->whereRaw('( subject LIKE "%'.$input['subject'].';%" OR subject LIKE "%'.$input['subject'].'" )');
                }
                if(!empty($input['gallery']) && $attribute!='gallery') {
                	$query->where('gallery', 'LIKE', '%'.$input['gallery'].'%');
                }
                if(!empty($input['year-range'])) {
                	$range = explode(',', $input['year-range']);
                	// dd("where('date_earliest', '>', $range[0])->where('date_latest', '<', $range[1])");
                	$query->where('date_earliest', '>', $range[0])->where('date_latest', '<', $range[1]);
                }

                return $query;
            })
		->remember(30)
		->get();

		$formated_list=array();
		foreach ($unformated_list as $result) {
			$values = explode($delimiter, $result->$attribute);
			if ($only_first) {
				$single_value = trim($values[0]);
				if (!isSet($formated_list[$single_value])) $formated_list[$single_value] = 0;
				$formated_list[$single_value] += $result->pocet;
			} else {
				foreach ($values as $single_value) {
					$single_value = trim($single_value);					
					if (!isSet($formated_list[$single_value])) $formated_list[$single_value] = 0;
					$formated_list[$single_value] += $result->pocet;
				}
			}
		}
		arsort($formated_list);

		$return_list = array();
		foreach ($formated_list as $key => $value) {
			$single_value = $key;
			if ($attribute=='author') $single_value = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $key);
			$return_list[$key] = "$single_value ($value)";
		}

		return $return_list;

	}

	public function isFreeDownload()
	{
		return ($this->attributes['free_download'] && !empty($this->attributes['iipimg_url']));
	}

	public function isForReproduction()
	{
		return ($this->attributes['gallery'] == 'Slovenská národná galéria, SNG');
	}

	public function download() {

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

	public  function index() {
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
	        	'gallery' => $this->attributes['gallery']
	        ];
	        return $client->index([
	        	'index' => Config::get('app.elasticsearch.index'),
	        	'type' =>  self::ES_TYPE,
	        	'id' => $this->attributes['id'],
	        	'body' =>$data,
        	]);		
	}

}