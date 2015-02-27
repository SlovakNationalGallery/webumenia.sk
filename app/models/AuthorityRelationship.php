<?php 

class AuthorityRelationship extends Eloquent {
	protected $table = 'authority_relationships';
	public $timestamps = false;
	protected $fillable = array(
			'authority_id',
			'realted_authority_id',
			'name',
			'type',
		);

}