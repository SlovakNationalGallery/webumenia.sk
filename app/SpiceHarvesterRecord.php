<?php
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class SpiceHarvesterRecord extends Model
{
    use SoftDeletes;

    protected $softDelete = true;
    protected $dates = ['deleted_at'];

    public function harvest()
    {
        return $this->belongsTo('SpiceHarvesterHarvest', 'harvest_id');
    }

    public function item()
    {
        return $this->belongsTo('Item', 'item_id');
    }
}
