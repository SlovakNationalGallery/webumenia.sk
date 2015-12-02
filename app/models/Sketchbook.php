<?php

class Sketchbook extends Eloquent {

    // const ARTWORKS_DIR = '/images/skicare/';
    
    public function item()
    {
        return $this->hasOne('Item');
    }

    public function scopePublished($query)
    {
        return $query->where('publish', '=', 1);
    }

}
