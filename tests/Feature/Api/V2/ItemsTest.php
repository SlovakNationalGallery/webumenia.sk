<?php

namespace Tests\Feature\Api\V2;

use App\Authority;
use App\Item;
use App\ItemImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;

class ItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_detail()
    {
        $authority = Authority::factory()->create(['name' => 'Wouwerman, Philips']);
        $item_image = ItemImage::factory()->make(['iipimg_url' => 'test_iipimg_url']);
        $item = Item::factory()->create([
            'id' => 'test_id',
            'title' => 'test_title',
            'author' => 'Wouwerman, Philips; Věšín, Jaroslav',
            'dating' => 2000,
            'date_earliest' => 2000,
            'date_latest' => 2010,
            'description' => 'test_description',
            'image_ratio' => 1.5,
            'medium' => 'test_medium',
            'measurement' => 'test_measurements',
        ]);

        $item->authorities()->attach($authority, ['role' => 'autor/author']);
        $item_image->item()->associate($item);
        $item_image->save();

        Config::set('app.iip_public', 'https://img.test.sk');

        $this->getJson('/api/v2/items/test_id')->assertExactJson([
            'data' => [
                'id' => 'test_id',
                'title' => 'test_title',
                'authorities' => [
                    [
                        ...$authority->only([
                            'id',
                            'name',
                            'biography',
                            'has_image',
                            'birth_date',
                            'death_date',
                            'birth_place',
                            'death_place',
                            'image_path',
                        ]),
                        'role' => 'autor',
                        'image_path' => $authority->getImagePath(),
                    ],
                ],
                'authors' => ['Wouwerman, Philips', 'Věšín, Jaroslav'],
                'dating' => '2000',
                'date_earliest' => 2000,
                'date_latest' => 2010,
                'description' => 'test_description',
                'image_ratio' => 1.5,
                'medium' => 'test_medium',
                'measurements' => ['test_measurements'],
                'images' => [
                    [
                        'deep_zoom_url' => 'https://img.test.sk/zoom/?path=test_iipimg_url.dzi',
                    ],
                ],
            ],
        ]);
    }

    public function test_multiple()
    {
        $items = Item::factory()->count(3)->create();
        $response = $this->getJson('/api/v2/items/?ids[]=' . $items->pluck('id')->implode('&ids[]='));

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
        foreach ($items as $item) {
            $response->assertJsonFragment([
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'image_ratio' => $item->image_ratio,
                'medium' => $item->medium,
                'measurements' => [$item->measurement],
                'images' => [],
            ]);
        }
    }
}
