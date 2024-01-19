<?php

namespace App;

use App\Enums\FrontendEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemFrontend extends Model
{
    use HasFactory;

    protected $fillable = [
        'frontend',
        'item_id',
    ];

    protected $casts = [
        'frontend' => FrontendEnum::class,
    ];

    public $timestamps = false;

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}