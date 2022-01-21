<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    protected $keyType = 'string';

    protected $fillable = array(
        'id',
        'code',
    );
}
