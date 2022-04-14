<?php

namespace App;

use App\Concerns\Translation;
use Illuminate\Database\Eloquent\Model;

class ShuffledItemTranslation extends Model
{
    use Translation;

    public $timestamps = false;

    protected $fillable = ['filters'];
    protected $casts = [
        'filters' => 'array',
    ];
}
