<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorityEvent extends Model
{
    protected $table = 'authority_events';
    public $timestamps = false;
    protected $fillable = array(
            'id',
            'authority_id',
            'event',
            'prefered',
            'place',
            'start_date',
            'end_date',
        );
}
