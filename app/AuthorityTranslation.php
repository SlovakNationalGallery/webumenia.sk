<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorityTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['type_organization', 'biography', 'birth_place', 'death_place', 'roles'];

    protected $casts = [
        'roles' => 'array',
    ];

    // return empty array instead of null for attributes casted as array
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }

        return parent::castAttribute($key, $value);
    }

}
