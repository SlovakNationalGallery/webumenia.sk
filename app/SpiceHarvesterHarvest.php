<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class SpiceHarvesterHarvest extends Model
{

    const STATUS_QUEUED      = 'queued';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED   = 'completed';
    const STATUS_ERROR       = 'error';
    const STATUS_DELETED     = 'deleted';
    const STATUS_KILLED      = 'killed';

    public static $types = ['item' => 'Dielo', 'author' => 'Autorita'];

    protected $appends = array('from');
    public static $datum;

    public static $rules = array(
        'base_url' => 'required',
        'metadata_prefix' => 'required',
        );

    public function records()
    {
        return $this->hasMany('SpiceHarvesterRecord', 'harvest_id');
    }

    public function collection()
    {
        return $this->belongsTo('Collection');
    }

    public function getFromAttribute()
    {
        return $this->start_from;
    }
}
