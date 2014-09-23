<?php

class Collection extends Eloquent {

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
    	return URL::to('sekcia/' . $this->attributes['id']);
    }

    public function getShortTextAttribute($value)
    {
        $string = strip_tags($this->attributes['text']);
        $string = $string;
        $string = substr($string, 0, 160);
        return substr($string, 0, strrpos($string, ' ')) . " ...";
    }

}