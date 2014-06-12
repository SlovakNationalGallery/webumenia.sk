<?php

class Item extends Eloquent {

protected $fillable = array(
	'id',
	'author',
	'title',
	'work_type',
	'work_level',
	'topic',
	'measurement',
	'dating',
	'date_earliest',
	'date_latest',
	'medium',
	'technique',
	'inscription',
	'state_edition',
	'integrity',
	'integrity_work',
	'gallery',
	'img_url',
	'item_type',
);

protected $guarded = array('featured');

}