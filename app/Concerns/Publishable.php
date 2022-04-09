<?php

namespace App\Concerns;

use Illuminate\Support\Carbon;

trait Publishable
{
    public function initializePublishable()
    {
        $this->casts['is_published'] = 'boolean';
        $this->casts['published_at'] = 'datetime';
    }

    public function getIsPublishedAttribute()
    {
        if (is_null($this->published_at)) {
            return false;
        }

        return $this->published_at->isPast();
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
