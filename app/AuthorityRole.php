<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorityRole extends Model
{
    protected $table = 'authority_roles';
    public $timestamps = false;
    protected $fillable = array(
        'authority_id',
        'role',
    );
}
