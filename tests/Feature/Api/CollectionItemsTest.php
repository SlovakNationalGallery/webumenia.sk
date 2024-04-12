<?php

namespace Tests\Feature\Api;

use App\Collection;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\RecreateSearchIndex;
use Tests\TestCase;

class CollectionItemsTest extends TestCase
{
    use RefreshDatabase;
    use RecreateSearchIndex;

    public function testIndexWebumeniaUrl()
    {
        Item::factory(2)->create(['author' => 'author-1']);
        Item::factory()->create(['author' => 'author-2']);
        app(ItemRepository::class)->refreshIndex();

        $collection = Collection::factory()->create([
            'url' => 'https://www.webumenia.sk/katalog-new?filter[author]=author-1',
        ]);

        $url = route('api.collections.items.index', [
            'collection' => $collection,
            'size' => 3,
        ]);
        $response = $this->get($url);
        $response->assertJsonCount(2, 'data');
    }

    public function testIndexMoravskaGalerieUrl()
    {
        Item::factory(2)->create(['author' => 'author-1']);
        Item::factory()->create(['author' => 'author-2']);
        app(ItemRepository::class)->refreshIndex();

        $collection = Collection::factory()->create([
            'url' => 'https://sbirky.moravska-galerie.cz/?author=author-1',
        ]);

        $url = route('api.collections.items.index', [
            'collection' => $collection,
            'size' => 3,
        ]);
        $response = $this->get($url);
        $response->assertJsonCount(2, 'data');
    }
}
