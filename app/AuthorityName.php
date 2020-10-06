<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorityName extends Model
{
    protected $table = 'authority_names';
    public $timestamps = false;
    protected $fillable = array(
            'name',
            'prefered',
        );

    public function authority()
    {
        return $this->belongsTo(Authority::class);
    }
}
