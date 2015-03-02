<?php 
class Link extends Eloquent {

	protected $fillable = array(
		'url',
		'label',
	);

    public function linkable()
    {
        return $this->morphTo();
    }

}
