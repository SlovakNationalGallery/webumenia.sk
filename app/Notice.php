<?php

namespace App;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Notice extends Model implements TranslatableContract
{
    use Translatable;
    
    public $translatedAttributes = ['content'];
    protected $fillable = ['publish', 'alert_class'];

    public function scopeCurrent(Builder $query)
    {
        return $query->where('publish', true)->withTranslation()->first();
    }
}
