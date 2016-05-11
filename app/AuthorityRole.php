<?php

class AuthorityRole extends Eloquent
{
    protected $table = 'authority_roles';
    public $timestamps = false;
    protected $fillable = array(
        'authority_id',
        'role',
    );
}
