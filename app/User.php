<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    public static $roles = ['admin', 'editor'];

    protected $fillable = [
        'name', 'email', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $rules = array(
        'name' => 'required',
        'role' => 'in:admin,editor|required',
        'email' => 'email|required',
        'username' => 'required',
    );
}
