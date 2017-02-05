<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{

    const STATUS_QUEUED      = 'queued';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED   = 'completed';
    const STATUS_ERROR       = 'error';
    const STATUS_DELETED     = 'deleted';
    const STATUS_KILLED      = 'killed';

    public static $cz_work_types = [
        'Ar' => 'architektúra',
        'F' =>  'fotografia',
        'G' =>  'grafika',
        'U' =>  'úžitkové umenie',
        'M' =>  'iné médiá',
        'K' =>  'kresba',
        'O' =>  'maliarstvo',
        'P' =>  'sochárstvo',
        ];

    public static $rules = array(
        'name' => 'required',
    );

    public function records()
    {
        return $this->hasMany('App\ImportRecord');
    }

    public function lastRecord()
    {
      return $this->records->last();
    }

}
