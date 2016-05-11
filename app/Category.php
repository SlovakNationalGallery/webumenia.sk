<?php

class Category extends Eloquent
{

    public function articles()
    {
        return $this->hasMany('Article');
    }
}
