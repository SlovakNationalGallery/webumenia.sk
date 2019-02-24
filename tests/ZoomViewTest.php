<?php


namespace Tests;


use App\Item;
use App\ItemImage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ZoomViewTest extends TestCase
{
    use DatabaseMigrations;

    public function testMultipleImages() {
        $item = $this->createItem(['id' => uniqid()]);
        $item->save();

        $image = $this->createItemImage([
            'iipimg_url' => true
        ]);
        $image->item()->associate($item);
        $image->save();

        $response = $this->route('get', 'item.zoom', ['id' => $item->id]);
        $data = $response->original->getData();

        $this->assertCount(1, $data['images']);
        $this->assertEquals(0, $data['index']);
    }

    public function testRelatedItems() {
        $related_work = uniqid();

        $item = $this->createItem([
            'id' => uniqid(),
            'related_work' => $related_work,
            'related_work_order' => 2,
        ]);
        $item->save();

        $related_item = $this->createItem([
            'id' => uniqid(),
            'related_work' => $related_work,
            'related_work_order' => 1,
        ]);
        $related_item->save();

        $image = $this->createItemImage(['iipimg_url' => true]);
        $image->item()->associate($item);
        $image->save();

        $related_image = $this->createItemImage(['iipimg_url' => true]);
        $related_image->item()->associate($related_item);
        $related_image->save();

        $response = $this->route('get', 'item.zoom', ['id' => $item->id]);
        $data = $response->original->getData();

        $this->assertCount(2, $data['images']);
        $this->assertEquals(1, $data['index']);
    }

    public function testPrioritizedMultipleImages() {
        $related_work = uniqid();

        $item = $this->createItem([
            'id' => uniqid(),
            'related_work' => $related_work,
            'related_work_order' => 2,
        ]);
        $item->save();

        $related_item = $this->createItem([
            'id' => uniqid(),
            'related_work' => $related_work,
            'related_work_order' => 1,
        ]);
        $related_item->save();

        $count = 2;
        for ($i = 0; $i < $count; $i++) {
            $image = $this->createItemImage([
                'iipimg_url' => true,
                'order' => $i,
            ]);
            $image->item()->associate($item);
            $image->save();
        }

        $related_image = $this->createItemImage(['iipimg_url' => true]);
        $related_image->item()->associate($related_item);
        $related_image->save();

        $response = $this->route('get', 'item.zoom', ['id' => $item->id]);
        $data = $response->original->getData();

        $this->assertCount($count, $data['images']);
        $this->assertEquals(0, $data['index']);
    }

    protected function createItem(array $data = []) {
        $item = new Item();

        // set defaults
        $item->work_type = '';
        $item->identifier = '';
        $item->title = '';
        $item->author = '';
        $item->topic = '';
        $item->place = '';
        $item->date_earliest = date('Y');
        $item->date_latest = date('Y');
        $item->medium = '';
        $item->technique = '';
        $item->gallery = '';

        foreach ($data as $key => $value) {
            $item->$key = $value;
        }

        return $item;
    }

    protected function createItemImage(array $data = []) {
        $image = new ItemImage();

        foreach ($data as $key => $value) {
            $image->$key = $value;
        }

        return $image;
    }
}