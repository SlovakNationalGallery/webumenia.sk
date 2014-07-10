<?php

class SpiceHarvesterRecord extends Eloquent {

	use SoftDeletingTrait;
	protected $dates = ['deleted_at'];

	public function harvest()
    {
        return $this->belongsTo('SpiceHarvesterHarvest', 'harvest_id');
    }
}