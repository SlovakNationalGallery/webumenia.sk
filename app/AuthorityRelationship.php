<?php

class AuthorityRelationship extends Eloquent
{
    protected $table = 'authority_relationships';
    public $timestamps = false;
    protected $fillable = array(
            'authority_id',
            'related_authority_id',
            'name',
            'type',
        );
}
