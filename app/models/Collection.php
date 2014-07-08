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

}