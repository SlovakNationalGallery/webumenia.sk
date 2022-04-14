<?php

namespace Tests\Unit;

use App\Item;
use App\ItemImage;
use App\ShuffledItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ShuffledItemTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_crop_url()
    {
        $item = factory(Item::class)->create();
        $item->images()->save(
            factory(ItemImage::class)->make([
                'iipimg_url' => '/path/to/image.jp2',
            ])
        );

        $shuffledItem = factory(ShuffledItem::class)->create([
            'item_id' => $item->id,
            'crop' => [
                'x' => 0.5,
                'y' => 0.6,
                'width' => 0.7,
                'height' => 0.8,
            ],
        ]);

        $this->assertEquals(
            'http://127.0.0.1:8002/?FIF=/path/to/image.jp2&WID=1600&RGN=0.5,0.6,0.7,0.8&CVT=jpeg',
            $shuffledItem->crop_url
        );
    }
}
