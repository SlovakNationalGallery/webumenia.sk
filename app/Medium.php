<?php

namespace App;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Medium extends Model implements TranslatableContract
{
    use NodeTrait;
    use Translatable;

    public array $translatedAttributes = ['name'];

    protected $fillable = ['parent_id'];

    public function getFullName(string $locale): string
    {
        return $this->getAncestors()
            ->push($this)
            ->map->translate($locale)
            ->filter()
            ->pluck('name')
            ->join('/');
    }
}
