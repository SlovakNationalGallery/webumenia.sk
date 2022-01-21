<?php

namespace App\Listeners;

use App\Events\ItemPrimaryImageChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;

class ItemPrimaryImageChangedListener implements ShouldQueue
{
    /** @var ColorExtractor */
    protected $extractor;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ColorExtractor $extractor) {
        $this->extractor = $extractor;
    }

    /**
     * Handle the event.
     *
     * @param  ItemPrimaryImageChanged  $event
     * @return void
     * @throws \Exception
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

        $colors = $this->extractor->extract($filename, config('items.colors.count'));
        $colors = collect($colors)
            ->mapWithKeys(function ($amount, $int) {
                return [Color::fromIntToHex($int) => $amount];
            })
            ->sort()
            ->reverse();

        $item->colors = $colors;
        $item->save();
    }
}
