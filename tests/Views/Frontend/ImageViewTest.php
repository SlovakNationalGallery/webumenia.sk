<?php

namespace Tests\Views\Frontend;

use App\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImageViewTest extends TestCase
{
    use RefreshDatabase;

    public function testGetResize()
    {
        $item = Item::factory()->webumeniaFrontend()->create();
        $response = $this->get(sprintf('/dielo/nahlad/%s/800', $item->id));
        $response->assertStatus(200);
    }
}
