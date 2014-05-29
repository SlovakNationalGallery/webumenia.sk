<?php

class SpiceHarvesterHarvest extends Eloquent {

    const STATUS_QUEUED      = 'queued';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED   = 'completed';
    const STATUS_ERROR       = 'error';
    const STATUS_DELETED     = 'deleted';
    const STATUS_KILLED      = 'killed';

    protected $appends = array('start_from');

	public function records()
    {
        return $this->hasMany('SpiceHarvesterRecords');
    }

 	public function getStartFromAttribute()
    {
        return $this->start_from;
    }    
}