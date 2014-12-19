<?php

class SpiceHarvesterHarvest extends Eloquent {

    const STATUS_QUEUED      = 'queued';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED   = 'completed';
    const STATUS_ERROR       = 'error';
    const STATUS_DELETED     = 'deleted';
    const STATUS_KILLED      = 'killed';


    protected $appends = array('from');
    public static $datum;

    public static $rules = array(
        'base_url' => 'required',
        'metadata_prefix' => 'required',
        'set_spec' => 'required',
        'set_name' => 'required'
        );

	public function records()
    {
        return $this->hasMany('SpiceHarvesterRecords');
    }

 	public function getFromAttribute()
    {
        return $this->start_from;
    }    
}