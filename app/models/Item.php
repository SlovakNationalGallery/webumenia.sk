<?php

class Item extends Eloquent {

    const ARTWORKS_DIR = '/images/diela/';

	protected $fillable = array(
		'id',
		'author',
		'title',
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

    protected $appends = array('measurements');

	public $incrementing = false;

	protected $guarded = array('featured');


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
		$authors_array = explode('; ', $this->attributes['author']);
		$authors = array();
		foreach ($authors_array as $author) {
			$authors[] = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $author);
		}

		return $authors;
	}

	public function getMeasurementsAttribute($value)
	{
		$measurements_array = explode(';', $this->attributes['measurement']);
		$measurements = array();
		foreach ($measurements_array as $measurement) {
			$measurement = explode(' ', $measurement, 2);
			$measurements[$measurement[0]] = $measurement[1];
		}

		return $measurements;
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

}