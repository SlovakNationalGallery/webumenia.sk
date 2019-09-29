<?php

namespace Tests\BrowserKit;

use App\Item;
use App\ItemImage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
        $related_work = $this->faker->word;
        $author = $this->faker->name;
        $related_items = [];
        for ($i = 0; $i < $count = 2; $i++) {
            $item = factory(Item::class)->create([
                'related_work' => $related_work,
                'related_work_order' => $i,
                'author' => $author,
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
        $related_work = $this->faker->word;
        $author = $this->faker->name;
        $item = factory(Item::class)->create([
            'related_work' => $related_work,
            'related_work_order' => 2,
            'author' => $author,
        ]);

        $related_item = factory(Item::class)->create([
            'related_work' => $related_work,
            'related_work_order' => 1,
            'author' => $author,
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