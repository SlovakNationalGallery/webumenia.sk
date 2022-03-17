<?php

namespace App;

use App\Concerns\DelegatesAttributes;
use Illuminate\Database\Eloquent\Model;

class ShuffledItem extends Model
{
    use DelegatesAttributes;

    protected $delegated = [
        'title' => 'item',
        'dating_formatted' => 'item',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getAuthorLinksAttribute()
    {
        return $this->item->authors_with_authorities->map(
            fn($a) => (object) [
                'label' => formatName($a->name),
                'url' => isset($a->authority)
                    ? $a->authority->getUrl()
                    : route('frontend.catalog.index', ['author' => $a->name]),
            ]
        );
    }
}
