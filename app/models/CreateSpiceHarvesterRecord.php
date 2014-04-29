<?php

class SpiceHarvesterRecord extends Eloquent {

	public function harvest()
    {
        return $this->belongsTo('SpiceHarvesterHarvest', 'harvest_id');
    }
}