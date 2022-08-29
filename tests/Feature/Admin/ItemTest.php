<?php

namespace Tests\Feature\Admin;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function testSearch()
    {
        $editor = factory(User::class)->create(['role' => 'editor']);
        $items = factory(Item::class, 2)->create();

        $this
        ->actingAs($editor)
            ->get(route('item.search', ['search' => $items[0]->id]))
            ->assertStatus(200)
            ->assertSeeText($items[0]->id)
            ->assertDontSeeText($items[1]->id);
    }
}