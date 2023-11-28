<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];
}
