<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportRecord extends Model
{

    public function import()
    {
        return $this->belongsTo('App\Import');
    }
}
