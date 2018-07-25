<?php

namespace Tests\View;

use App\Item;
use App\ItemImage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ZoomViewTest extends TestCase
{
    use DatabaseMigrations;

    public function testMultipleImages() {
        $item = $this->createModel(Item::class);

        $image = $this->createModel(ItemImage::class, [], $save = false);
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
            $item = $this->createModel(Item::class, [
                'related_work' => $related_work,
                'related_work_order' => $i,
                'author' => $author,
            ]);

            $image = $this->createModel(ItemImage::class, [], $save = false);
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
        $item = $this->createModel(Item::class, [
            'related_work' => $related_work,
            'related_work_order' => 2,
            'author' => $author,
        ]);

        $related_item = $this->createModel(Item::class, [
            'related_work' => $related_work,
            'related_work_order' => 1,
            'author' => $author,
        ]);

        $count = 2;
        for ($i = 0; $i < $count; $i++) {
            $image = $this->createModel(ItemImage::class, [], $save = false);
            $image->item()->associate($item);
            $image->save();
        }

        $related_item_image = $this->createModel(ItemImage::class, [], $save = false);
        $related_item_image->item()->associate($related_item);
        $related_item_image->save();

        $response = $this->route('get', 'item.zoom', ['id' => $item->id]);
        $data = $response->original->getData();

        $this->assertCount($count, $data['fullIIPImgURLs']);
        $this->assertEquals(0, $data['index']);
    }
}