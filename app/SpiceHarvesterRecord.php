<?php



namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SpiceHarvesterRecord extends Model
{
    use SoftDeletes;

    protected $softDelete = true;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'identifier',
        'type',
    ];

    public function harvest()
    {
        return $this->belongsTo('App\SpiceHarvesterHarvest', 'harvest_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Item', 'item_id');
    }

    public function scopeFailed($query)
    {
        return $query->whereNotNull('failed_at');
    }

    public function process(callable $onProcess)
    {
        try {
            $onProcess();

            $this->failed_at = null;
            $this->error_message = null;
        } catch (\Exception $e) {
            $this->failed_at = Carbon::now();
            $this->error_message = $e->getMessage();
        } finally {
            if (!$this->deleted_at) {
                $this->save();
            }
        }
    }
}
