<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];
}
