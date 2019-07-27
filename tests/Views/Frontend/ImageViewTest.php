<?php

namespace Tests\Views\Frontend;

use App\Item;

class ImageViewTest extends FrontendViewTestCase
{
    public function testGetResize()
    {
        $item = factory(Item::class)->create();
        $response = $this->get(sprintf('/dielo/nahlad/%d/800', $item->id));
        $response->assertStatus(200);
    }
}