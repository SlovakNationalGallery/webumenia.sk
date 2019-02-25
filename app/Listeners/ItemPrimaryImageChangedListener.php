<?php

namespace App\Listeners;

use App\Descriptors\ColorDescriptor;
use App\Events\ItemPrimaryImageChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

class ItemPrimaryImageChangedListener implements ShouldQueue
{
    /** @var ColorDescriptor */
    protected $colorDescriptor;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        ColorDescriptor $colorDescriptor
    ) {
        $this->colorDescriptor = $colorDescriptor;
    }

    /**
     * Handle the event.
     *
     * @param  ItemPrimaryImageChanged  $event
     * @return void
     */
    public function handle(ItemPrimaryImageChanged $event)
    {
        $item = $event->item;
        $filename = $item->getImagePath($full = true);

        if (!is_file($filename)) {
            throw new \Exception(sprintf("File '%s' does not exist", $filename));
        }

        if (!@getimagesize($filename)) {
            throw new \Exception(sprintf("File '%s' is not valid image", $filename));
        }

        $item->color_descriptor = $this->colorDescriptor->describe($filename);
        $item->save();
    }
}
