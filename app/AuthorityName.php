<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthorityName extends Model
{
    protected $table = 'authority_names';
    public $timestamps = false;
    protected $fillable = array(
            'authority_id',
            'name',
            'prefered',
        );

    public function authority()
    {
        return $this->belongsTo(Authority::class);
    }

    public function scopeFirstNameLastName($query, $name)
    {
        $query->where(
            DB::raw('concat(substring_index(name, ", ", -1), " ", substring_index(name, ", ", 1))'),
            $name
        );
    }
}
