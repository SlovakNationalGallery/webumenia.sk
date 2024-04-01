<?php

namespace Tests\Feature\Api\Collections;

use App\Collection;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\RecreateSearchIndex;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    use RecreateSearchIndex;

    public function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'http://localhost/api/v1/items*' => function (Request $request) {
                $testResponse = $this->get($request->url());
                return Http::response(
                    $testResponse->getContent(),
                    $testResponse->getStatusCode(),
                    $testResponse->headers->all()
                );
            },
        ]);
    }

    public function testWithUrl()
    {
        Item::factory()->create(['author' => 'author-1']);
        Item::factory()->create(['author' => 'author-2']);
        $collection = Collection::factory()
            ->published()
            ->create([
                'url' => 'https://www.webumenia.sk/katalog-new?filter[author][]=author-1',
            ]);

        // Ensure all data is indexed
        app(ItemRepository::class)->refreshIndex();

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
            'filter_items_count' => 1,
        ]);

        Http::assertSentCount(1);
    }

    public function testEmptyUrl()
    {
        $collection = Collection::factory()
            ->published()
            ->create(['url' => null]);

        $response = $this->getJson(route('api.collections.index'));
        $response->assertJsonCount(1, 'data')->assertJsonPath('data.0', [
            'id' => $collection->id,
            'name' => $collection->name,
            'text' => $collection->text,
            'filter_items_url' => null,
            'filter_items_count' => null,
        ]);

        Http::assertSentCount(0);
    }
}
