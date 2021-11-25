<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_url',
        'target_url',
        'is_enabled',
    ];

    public static $rules = [
        'source_url' => 'required|unique:redirects',
        'target_url' => 'required',
        'is_enabled' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('redirects');
        });
        static::deleted(function () {
            Cache::forget('redirects');
        });
    }

    public function scopeEnabled($query)
    {
        $query->where('is_enabled', 1);
    }
}
