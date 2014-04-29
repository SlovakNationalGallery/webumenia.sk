<?php

class SpiceHarvesterHarvest extends Eloquent {

	public function records()
    {
        return $this->hasMany('SpiceHarvesterRecords');
    }
}