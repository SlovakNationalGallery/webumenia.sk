<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class AuthorityRole extends Model
{
    use Translatable;

    protected $table = 'authority_roles';
    public $timestamps = false;
    public $translatedAttributes = ['role'];
    protected $fillable = array(
        'id',
        'type',
        'role'
    );
}
