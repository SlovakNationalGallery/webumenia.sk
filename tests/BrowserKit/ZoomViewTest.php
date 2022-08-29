<?php

namespace Tests\BrowserKit;

use App\Item;
use App\ItemImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTestCase;

class ZoomViewTest extends BrowserKitTestCase
{
    use RefreshDatabase;

    public function testMultipleImages()
    {
        $item = Item::factory()->create();

        $image = ItemImage::factory()->make();
        $image->item()->associate($item);
        $image->save();

        $response = $this->route('get', 'item.zoom', ['id' => $item->id]);
        $data = $response->original->getData();

        $this->assertCount(1, $data['fullIIPImgURLs']);
        $this->assertEquals(0, $data['index']);
    }

    public function testRelatedItems()
    {
        $related_items = [];
        for ($i = 0; $i < ($count = 2); $i++) {
            $item = Item::factory()->create([
                'related_work' => 'some_related_work',
                'related_work_order' => $i,
                'author' => 'some_author',
            ]);

            $image = ItemImage::factory()->make();
            $image->item()->associate($item);
            $image->save();

            $related_items[] = $item;
        }

        $response = $this->route('get', 'item.zoom', ['id' => $related_items[0]->id]);
        $data = $response->original->getData();

        $this->assertCount($count, $data['fullIIPImgURLs']);
        $this->assertEquals(0, $data['index']);
    }

    public function testPrioritizedMultipleImages()
    {
        $count = 2;
        $item = Item::factory()->create([
            'related_work' => 'some_related_work',
            'related_work_order' => 2,
            'author' => 'some_author',
        ]);

        $related_item = Item::factory()->create([
            'related_work' => 'some_related_work',
            'related_work_order' => 1,
            'author' => 'some_author',
        ]);

        ItemImage::factory()
            ->count(2)
            ->make()
            ->each(function (ItemImage $image) use ($item) {
                $image->item()->associate($item);
                $image->save();
            });

        $related_item_image = ItemImage::factory()->make();
        $related_item_image->item()->associate($related_item);
        $related_item_image->save();

        $response = $this->route('get', 'item.zoom', ['id' => $item->id]);
        $data = $response->original->getData();

        $this->assertCount($count, $data['fullIIPImgURLs']);
        $this->assertEquals(0, $data['index']);
    }
}
