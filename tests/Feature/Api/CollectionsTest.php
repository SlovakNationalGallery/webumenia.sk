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
                'main_image' => 'image.jpg',
            ]);

        $response = $this->get(route('api.collections.index'));
        $response->assertJsonCount(1, 'data')->assertJsonPath('data.0', [
            'id' => $collection->id,
            'name' => $collection->name,
            'text' => $collection->text,
            'header_image_src' => 'http://localhost/images/kolekcie/image.jpg',
            'header_image_srcset' =>
                'http://localhost/images/kolekcie/image.1920.jpg 1920w, ' .
                'http://localhost/images/kolekcie/image.1400.jpg 1400w, ' .
                'http://localhost/images/kolekcie/image.jpg 1024w, ' .
                'http://localhost/images/kolekcie/image.640.jpg 640w',
            'filter_items_url' => route('api.v1.items.index', [
                'filter' => [
                    'author' => ['author-1'],
                ],
            ]),
        ]);
    }

    public function testIndexFeatured()
    {
        $featured = Collection::factory()
            ->published()
            ->featured()
            ->create();
        Collection::factory()
            ->published()
            ->create();

        $url = route('api.collections.index', [
            'featured' => true,
            'size' => 2,
        ]);
        $response = $this->get($url);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $featured->id);
    }

    public function testShow()
    {
        $collection = Collection::factory()->create([
            'url' => 'https://www.webumenia.sk/katalog-new?filter[author]=author-1',
            'main_image' => 'image.jpg',
        ]);

        $this->getJson(route('api.collections.show', $collection))->assertJsonPath('data', [
            'id' => $collection->id,
            'name' => $collection->name,
            'text' => $collection->text,
            'header_image_src' => 'http://localhost/images/kolekcie/image.jpg',
            'header_image_srcset' =>
                'http://localhost/images/kolekcie/image.1920.jpg 1920w, ' .
                'http://localhost/images/kolekcie/image.1400.jpg 1400w, ' .
                'http://localhost/images/kolekcie/image.jpg 1024w, ' .
                'http://localhost/images/kolekcie/image.640.jpg 640w',
            'filter_items_url' => route('api.v1.items.index', [
                'filter' => [
                    'author' => 'author-1',
                ],
            ]),
        ]);
    }
}
