<?php



namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    use HasFactory;
    protected $keyType = 'string';

    protected $fillable = array(
        'id',
        'code',
    );
}
