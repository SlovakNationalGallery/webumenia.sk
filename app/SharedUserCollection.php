<?php

namespace App;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SharedUserCollection extends Model
{
    protected $casts = [
        'items' => AsCollection::class
    ];

    protected $fillable = [
        'name',
        'author',
        'description',
        'items',
    ];

    protected static function booted()
    {
        static::creating(function ($collection) {
            $collection->public_id = $collection->public_id ?: Str::random(6);
            $collection->update_token = $collection->update_token ?: Str::random(16);
        });
    }
}
