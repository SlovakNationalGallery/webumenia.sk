<?php

namespace App\Traits;

trait HasLinks
{
    public function links()
    {
        return $this->morphMany(\App\Link::class, 'linkable');
    }

    public function linksForLocale()
    {
        return $this->morphMany(\App\Link::class, 'linkable')->where('locale', '=', app()->getLocale());
    }
}
