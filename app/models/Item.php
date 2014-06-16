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
		'measurement',
		'dating',
		'date_earliest',
		'date_latest',
		'medium',
		'technique',
		'inscription',
		'state_edition',
		'integrity',
		'integrity_work',
		'gallery',
		'img_url',
		'item_type',
	);

	public $incrementing = false;

	protected $guarded = array('featured');


	public function getImagePath($full=false) {

		$levels = 1;
	    $dirsPerLevel = 100;

	    $transformedWorkArtID = $this->hashcode($this->id);
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

	    $result_path = public_path() . self::ARTWORKS_DIR .  "$galleryDir/$path/$file/";

	    if (!file_exists($result_path)) {
	    	mkdir($result_path, 0777, true);
	    }

	    // if (!file_exists( $result_path)) $result_path = IMAGES_DIR . '/no-image'.$image_suffix.'.jpeg';
		return $result_path . "$file.jpeg";
	}

	private function intval32bits($value)
	{
	    $value = ($value & 0xFFFFFFFF);

	    if ($value & 0x80000000)
	        $value = -((~$value & 0xFFFFFFFF) + 1);

	    return $value;
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