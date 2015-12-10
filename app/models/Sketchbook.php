<?php

use Conner\Tagging\TaggingUtil;

class Sketchbook extends Eloquent {

    // const ARTWORKS_DIR = '/images/skicare/';
    
	protected $fillable = [
				'title',
				'width',
				'height',
				'order',
				'publish',
	];

	public static $rules = [
		'item_id' => 'required',
		// 'title' => 'required',
		'width' => 'numeric',
		'height' => 'numeric',
		'publish' => 'boolean',
	];

    public function item()
    {
        return $this->belongsTo('Item');
    }

    public function scopePublished($query)
    {
        return $query->where('publish', '=', 1);
    }

    public function getFileSizeAttribute()
    {
    	return ( File::exists($this->getPath() . $this->file )) ? File::size($this->getPath() . $this->file ) : false;
    }

    public function getFileAttribute($value)
    {
    	return ( File::exists($this->getPath() . $value )) ? $value : false;
    }

    public function getPath($create = false)
    {
	    $folder_name = $this->id; 
    	$path = storage_path() . '/skicare/' . $folder_name . '/';
    	if(!File::exists($path) && $create) {
    	    File::makeDirectory($path);
    	}
    	return $path;
    }

}
