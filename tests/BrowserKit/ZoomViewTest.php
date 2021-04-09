<?php

namespace Tests\BrowserKit;

use App\Item;
use App\ItemImage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\BrowserKitTestCase;

class ZoomViewTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    public function testMultipleImages() {
        $item = factory(Item::class)->create();

        $image = factory(ItemImage::class)->make();
        $image->item()->associate($item);
        $image->save();

        $response = $this->route('get', 'item.zoom', ['id' => $item->id]);
        $data = $response->original->getData();

        $this->assertCount(1, $data['fullIIPImgURLs']);
        $this->assertEquals(0, $data['index']);
    }

    public function testRelatedItems() {
        $related_items = [];
        for ($i = 0; $i < $count = 2; $i++) {
            $item = factory(Item::class)->create([
                'related_work' => 'some_related_work',
                'related_work_order' => $i,
                'author' => 'some_author',
            ]);

            $image = factory(ItemImage::class)->make();
            $image->item()->associate($item);
            $image->save();

            $related_items[] = $item;
        }

        $response = $this->route('get', 'item.zoom', ['id' => $related_items[0]->id]);
        $data = $response->original->getData();

        $this->assertCount($count, $data['fullIIPImgURLs']);
        $this->assertEquals(0, $data['index']);
    }

    public function testPrioritizedMultipleImages() {
        $item = factory(Item::class)->create([
            'related_work' => 'some_related_work',
            'related_work_order' => 2,
            'author' => 'some_author',
        ]);

        $related_item = factory(Item::class)->create([
            'related_work' => 'some_related_work',
            'related_work_order' => 1,
            'author' => 'some_author',
        ]);

        factory(ItemImage::class, $count = 2)->make()->each(function (ItemImage $image) use ($item) {
            $image->item()->associate($item);
            $image->save();
        });

        $related_item_image = factory(ItemImage::class)->make();
        $related_item_image->item()->associate($related_item);
        $related_item_image->save();

        $response = $this->route('get', 'item.zoom', ['id' => $item->id]);
        $data = $response->original->getData();

        $this->assertCount($count, $data['fullIIPImgURLs']);
        $this->assertEquals(0, $data['index']);
    }
}
