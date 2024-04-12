<?php

namespace App;

use App\Enums\FrontendEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'username',
        'frontend',
        'can_administer',
        'can_edit',
        'can_publish',
        'can_import',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public static $rules;

    public static function boot()
    {
        parent::boot();
        self::$rules = [
            'name' => 'required',
            'email' => 'email|required',
            'username' => 'required',
            'can_administer' => 'boolean|required',
            'can_edit' => 'boolean|required',
            'can_publish' => 'boolean|required',
            'can_import' => 'boolean|required',
            'frontend' => [Rule::enum(FrontendEnum::class), 'required'],
        ];
    }

    public function getPermissionsAttribute(): Collection
    {
        return collect(['can_administer', 'can_edit', 'can_publish', 'can_import'])->filter(
            fn($permission) => $this->$permission
        );
    }
}
