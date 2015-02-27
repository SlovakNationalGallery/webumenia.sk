<?php 

class AuthorityName extends Eloquent {
	protected $table = 'authority_names';
	public $timestamps = false;
	protected $fillable = array(
			'authority_id',
			'name',
			'prefered',
		);

}