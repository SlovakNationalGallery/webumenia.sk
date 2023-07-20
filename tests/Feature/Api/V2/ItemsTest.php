<?php

namespace Tests\Feature\Api\V2;

use App\Authority;
use App\Item;
use App\ItemImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
            'author' => 'Věšín, Jaroslav',
            'dating' => 2000,
            'date_earliest' => 2000,
            'date_latest' => 2010,
            'description' => 'test_description',
            'image_ratio' => 1.5,
        ]);

        $item->authorities()->attach($authority);
        $item_image->item()->associate($item);
        $item_image->save();

        $image_zoom = sprintf(
            '%s/zoom/?path=%s.dzi',
            config('app.iip_public'),
            urlencode('test_iipimg_url')
        );

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
                        'image_path' => $authority->getImagePath(),
                    ],
                ],
                'authors' => ['Wouwerman, Philips', 'Věšín, Jaroslav'],
                'dating' => '2000',
                'date_earliest' => 2000,
                'date_latest' => 2010,
                'description' => 'test_description',
                'image_ratio' => 1.5,
                'images_zoom' => [$image_zoom],
            ],
        ]);
    }
}
