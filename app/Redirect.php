<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_url',
        'target_url',
        'is_enabled',
    ];

    public function scopeEnabled($query)
    {
        $query->where('is_enabled', 1);
    }
}
