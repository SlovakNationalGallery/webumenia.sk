<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\ItemImageMapper;
use Tests\TestCase;

class ItemImageMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new ItemImageMapper();
        $row = [
            'iipimg_url' => ['/SNGBA/X100/SNG--G_23--1_2--_2013_02_20_--L2_WEB.jp2'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'iipimg_url' => '/SNGBA/X100/SNG--G_23--1_2--_2013_02_20_--L2_WEB.jp2',
        ];
        $this->assertEquals($expected, $mapped);
    }
}