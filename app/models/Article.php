<?php

class Article extends Eloquent {

    use Conner\Tagging\TaggableTrait;
    
    const ARTWORKS_DIR = '/images/clanky/';

    public static $rules = array(
        'name' => 'required',
        'text' => 'required',
        );

    // public function items()
 //    {
 //        return $this->belongsToMany('Item', 'collection_item', 'collection_id', 'item_id');
 //    }

	public function category()
    {
        return $this->belongsTo('Category');
    }

    public function getUrl()
    {
    	return URL::to('clanok/' . $this->attributes['url_slug']);
    }

    public function getShortTextAttribute($string)
    {
        $string = strip_tags($string);
        $string = $string;
        $string = substr($string, 0, 160);
        return substr($string, 0, strrpos($string, ' ')) . " ...";
    }

    public function getHeaderImage($full=false) {
        $relative_path = self::ARTWORKS_DIR . $this->attributes['main_image'];
        $path = ($full) ? public_path() . $relative_path : $relative_path;
        return $path;
    }

    public function getPublishedDateAttribute($value) {        
        return Carbon::parse($value)->format('d. m. Y'); //Change the format to whichever you desire
    }

    public function getTitleColorAttribute($value) {        
        return (!empty($value)) ? $value : '#fff';
    }

    public function getTitleShadowAttribute($value) {        
        return (!empty($value)) ? $value : '#777';
    }

}