<?php

namespace Tests\Feature\Api\Collections;

use App\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function testWithUrl()
    {
        $collection = Collection::factory()->create([
            'url' => 'https://www.webumenia.sk/katalog-new?filter[author]=author-1',
        ]);

        $this->getJson(route('api.collections.show', $collection))->assertJsonPath('data', [
            'id' => $collection->id,
            'name' => $collection->name,
            'text' => $collection->text,
            'filter_items_url' => route('api.v1.items.index', [
                'filter' => [
                    'author' => 'author-1',
                ],
            ]),
        ]);
    }

    public function testEmptyUrl()
    {
        $collection = Collection::factory()->create(['url' => null]);

        $this->getJson(route('api.collections.show', $collection))->assertJsonPath('data', [
            'id' => $collection->id,
            'name' => $collection->name,
            'text' => $collection->text,
            'filter_items_url' => null,
        ]);
    }
}
