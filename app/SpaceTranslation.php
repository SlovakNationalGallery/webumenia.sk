<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpaceTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'address',
        'opened_place',
        'description',
        'bibliography',
        'exhibitions',
        'archive',
    ];

}
