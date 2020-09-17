<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorityRole extends Model
{
    protected $table = 'authority_roles';
    public $timestamps = false;
    protected $fillable = array(
        'id',
        'type',
        'sk',
        'en'
    );
}
