<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorityTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['type_organization', 'biography', 'birth_place', 'death_place'];
}
