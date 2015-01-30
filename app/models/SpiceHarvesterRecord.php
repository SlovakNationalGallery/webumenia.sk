<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class SpiceHarvesterRecord extends Eloquent {
	use SoftDeletingTrait;

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