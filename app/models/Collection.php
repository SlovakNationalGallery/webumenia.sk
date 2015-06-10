<?php

class Collection extends Eloquent {

    const ARTWORKS_DIR = '/images/kolekcie/';

    public static $rules = array(
        'name' => 'required',
        'text' => 'required',
        );

	public function items()
    {
        return $this->belongsToMany('Item', 'collection_item', 'collection_id', 'item_id');
    }

    public function getUrl()
    {
    	return URL::to('kolekcia/' . $this->attributes['id']);
    }

    public function getShortTextAttribute($value)
    {
        $string = strip_tags($this->attributes['text']);
        $string = $string;
        $string = substr($string, 0, 160);
        return substr($string, 0, strrpos($string, ' ')) . " ...";
    }

    public function hasHeaderImage() {
        return file_exists(self::getHeaderImageForId($this->id, true));
    }

    public function getHeaderImage() {
        return self::getHeaderImageForId($this->id);
    }

    public  static function getHeaderImageForId($id, $full = false) {
        $relative_path = self::ARTWORKS_DIR . $id . '.jpg';
        $path = ($full) ? public_path() . $relative_path : $relative_path;
        return $path;
    }

}