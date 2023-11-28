<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use Translatable;

    public array $translatedAttributes = ['name'];
}
