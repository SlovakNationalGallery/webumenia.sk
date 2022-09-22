<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    public static $roles = ['admin', 'editor', 'importer'];

    protected $fillable = ['name', 'email', 'role', 'username'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public static $rules = [
        'name' => 'required',
        'role' => 'in:admin,editor,importer|required',
        'email' => 'email|required',
        'username' => 'required',
    ];
}
