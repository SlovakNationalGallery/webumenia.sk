<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediumTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];
}