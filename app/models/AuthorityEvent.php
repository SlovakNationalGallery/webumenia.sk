<?php 

class AuthorityEvent extends Eloquent {
	protected $table = 'authority_events';
	public $timestamps = false;
	protected $fillable = array(
			'authority_id',
			'event',
			'prefered',
			'place',
			'start_date',
			'end_date',
		);

}