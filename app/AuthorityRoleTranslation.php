<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorityRoleTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['role'];
}
