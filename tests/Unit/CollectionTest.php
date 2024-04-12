<?php

namespace Tests\Unit;

use App\Collection;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    public function testItemFilterFromWebumeniaUrl()
    {
        $collection = Collection::factory()->make([
            'url' =>
                'https://www.webumenia.sk/katalog-new?filter[work_type][]=maliarstvo&filter[work_type][]=fotografia',
        ]);

        $expected = [
            'work_type' => ['maliarstvo', 'fotografia'],
        ];
        $this->assertEquals($expected, $collection->item_filter);
    }

    public function testItemFilterFromMoravskaGalerieUrl()
    {
        $collection = Collection::factory()->make([
            'url' => 'https://sbirky.moravska-galerie.cz/?work_type=maliarstvo|fotografia',
        ]);

        $expected = [
            'work_type' => ['maliarstvo', 'fotografia'],
        ];
        $this->assertEquals($expected, $collection->item_filter);
    }
}
