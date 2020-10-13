<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait Publishable
{
    public function getIsPublishedAttribute()
    {
        if (!isset($this->published_at)) return false;
        return $this->published_at->isPast();
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<', Carbon::now());
    }
}
