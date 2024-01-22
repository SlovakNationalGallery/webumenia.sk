<?php

namespace App;

use App\Enums\FrontendEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemFrontend extends Model
{
    protected $fillable = [
        'frontend',
    ];

    protected $casts = [
        'frontend' => FrontendEnum::class,
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}