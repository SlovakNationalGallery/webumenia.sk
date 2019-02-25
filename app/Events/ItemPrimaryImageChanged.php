<?php

namespace App\Events;

use App\Item;
use Illuminate\Queue\SerializesModels;

class ItemPrimaryImageChanged extends Event
{
    use SerializesModels;

    /** @var Item */
    public $item;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }
}
