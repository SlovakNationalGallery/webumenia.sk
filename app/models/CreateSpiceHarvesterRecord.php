<?php

class SpiceHarvesterRecord extends Eloquent {

	protected $softDelete = true; 

	public function harvest()
    {
        return $this->belongsTo('SpiceHarvesterHarvest', 'harvest_id');
    }
}