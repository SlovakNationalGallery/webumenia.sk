<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function scopeWhereFirstNameLastName($query, $name)
    {
        $query->whereRaw(
            'case when instr(name, ", ") then ' .
            'concat(substring_index(name, ", ", -1), " ", substring_index(name, ", ", 1)) = ? ' .
            'else name = ? end',
            [$name, $name]
        );
    }
}
