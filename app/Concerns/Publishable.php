<?php

namespace App\Concerns;

use Illuminate\Support\Carbon;

trait Publishable
{
    public function getIsPublishedAttribute()
    {
        return !!$this->published_at;
    }

    public function setIsPublishedAttribute(bool $isPublished)
    {
        if ($this->is_published === $isPublished) {
            return;
        }
        if (!$isPublished) {
            return $this->attributes['published_at'] = null;
        }

        $this->attributes['published_at'] = Carbon::now();
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', Carbon::now());
    }
}
