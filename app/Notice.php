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

    protected $fillable = ['publish', 'alert_class', 'updated_by'];

    public static $rules = [
        'sk.content' => 'required|string',
        'alert_class' => 'required|in:info,warning,danger',
    ];

    public static function current()
    {
        return self::where('publish', true)->withTranslation()->first();
    }
}
