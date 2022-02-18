<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class FeaturedArtwork extends Model
{
    protected $fillable = [
        'item_id',
        'title',
        'description',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getIsPublishedAttribute() {
        return !!$this->published_at;
    }

    public function setIsPublishedAttribute(bool $isPublished) {
        if ($this->is_published === $isPublished) return;
        if (!$isPublished) return $this->attributes['published_at'] = null;

        $this->attributes['published_at'] = Carbon::now();
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', Carbon::now());
    }
}
