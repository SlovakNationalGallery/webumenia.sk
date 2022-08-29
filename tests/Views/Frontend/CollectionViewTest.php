<?php

namespace Tests\Views\Frontend;

use App\Collection;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionViewTest extends TestCase
{
    use RefreshDatabase;

    public function testGetIndex()
    {
        $response = $this->get('/kolekcie');
        $response->assertStatus(200);
    }

    public function testGetSuggestions()
    {
        $response = $this->get('/kolekcie/suggestions');
        $response->assertStatus(200);
    }

    public function testGetDetail()
    {
        $user = User::factory()->create();
        $collection = factory(Collection::class)->make();
        $collection->user()->associate($user);
        $collection->save();

        $response = $this->get(sprintf('/kolekcia/%d', $collection->id));
        $response->assertStatus(200);
    }
}
