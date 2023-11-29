<?php

namespace Tests\Unit;

use App\Item;
use App\ItemImage;
use App\ShuffledItem;
use Database\Factories\MediaFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ShuffledItemTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_crop_url()
    {
        $item = Item::factory()->create();
        $item->images()->save(
            ItemImage::factory()->make([
                'iipimg_url' => '/path/to/image.jp2',
            ])
        );

        $shuffledItem = ShuffledItem::factory()->create([
            'item_id' => $item->id,
            'crop' => [
                'x' => 0.5,
                'y' => 0.6,
                'width' => 0.7,
                'height' => 0.8,
            ],
        ]);

        $this->assertEquals(
            'http://127.0.0.1:8002/?FIF=/path/to/image.jp2&WID=1920&RGN=0.5,0.6,0.7,0.8&CVT=jpeg',
            $shuffledItem->crop_url
        );
    }

    public function test_toShuffleOrchestratorItem()
    {
        $shuffledItem = ShuffledItem::factory()->create([
            'filters' => [
                [
                    'url' =>
                        'http://test-url/en/katalog?work_type=maliarstvo&object_type=easel+painting&years-range=1500%2C1927',
                    'attributes' => [['name' => 'object_type', 'label' => 'easel painting']],
                ],
            ],
            'crop' => [
                'x' => 0.5,
                'y' => 0.6,
                'width' => 0.7,
                'height' => 0.8,
            ],
        ]);
        $media = MediaFactory::new()->create([
            'model_type' => ShuffledItem::class,
            'model_id' => $shuffledItem->id,
            'collection_name' => 'image',
        ]);

        $this->assertEquals(
            [
                'id' => $shuffledItem->id,
                'authorLinks' => $shuffledItem->authorLinks,
                'title' => $shuffledItem->title,
                'url' => route('dielo', $shuffledItem->item),
                'datingFormatted' => $shuffledItem->datingFormatted,
                'img' => [
                    'src' => $media->getUrl(),
                    'srcset' => $media->getSrcset(),
                ],
                'filters' => collect([
                    [
                        'url' =>
                            'http://test-url/en/katalog?work_type=maliarstvo&object_type=easel+painting&years-range=1500%2C1927',
                        'attributes' => collect([
                            [
                                'label' => trans('item.object_type'),
                                'value' => 'easel painting',
                                'url' => 'http://test-url/en/katalog?object_type=easel+painting',
                            ],
                        ]),
                    ],
                ]),
            ],
            $shuffledItem->toShuffleOrchestratorItem()
        );
    }
}
