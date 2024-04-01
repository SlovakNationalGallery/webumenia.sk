<?php

namespace Tests\Unit;

use App\Collection;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    public function testFilterItemsUrlFromWebumeniaUrl()
    {
        $collection = Collection::factory()->make([
            'url' =>
                'https://www.webumenia.sk/katalog-new?filter[work_type][]=maliarstvo&filter[work_type][]=fotografia',
        ]);

        $expected = route('api.v1.items.index', [
            'filter' => [
                'work_type' => ['maliarstvo', 'fotografia'],
            ],
        ]);

        $this->assertEquals($expected, $collection->filter_items_url);
    }
}
