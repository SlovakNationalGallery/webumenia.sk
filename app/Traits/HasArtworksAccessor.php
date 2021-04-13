<?php

namespace App\Traits;

trait HasArtworksAccessor
{
    public function getArtworksAttribute()
    {
        return $this->getMedia('artworks');
    }
}
