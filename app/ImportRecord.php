<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportRecord extends Model
{

    protected $dates = ['created_at', 'updated_at', 'started_at', 'completed_at'];
    public function import()
    {
        return $this->belongsTo('App\Import');
    }
}