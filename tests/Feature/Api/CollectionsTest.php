<?php

namespace Tests\Feature\Api;

use App\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionsTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $collection = Collection::factory()
            ->published()
            ->create([
                'url' => 'https://www.webumenia.sk/katalog-new?filter[author][]=author-1',
            ]);

        $response = $this->get(route('api.collections.index'));
        $response->assertJsonCount(1, 'data')->assertJsonPath('data.0', [
            'id' => $collection->id,
            'name' => $collection->name,
            'text' => $collection->text,
            'filter_items_url' => route('api.v1.items.index', [
                'filter' => [
                    'author' => ['author-1'],
                ],
            ]),
        ]);
    }

    public function testShow()
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
}
