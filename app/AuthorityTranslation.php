<?php

namespace App;

use App\Concerns\Translation;
use Illuminate\Database\Eloquent\Model;

class AuthorityTranslation extends Model
{
    use Translation;

    public $timestamps = false;
    protected $fillable = ['type_organization', 'biography', 'birth_place', 'death_place', 'roles'];

    protected $casts = [
        'roles' => 'array',
    ];

    protected function castAttribute($key, $value)
    {
        $casted = parent::castAttribute($key, $value);
        if ($this->getCastType($key) === 'array' && !is_array($value)) {
            return (array)$casted;
        }

        return $casted;
    }

}
