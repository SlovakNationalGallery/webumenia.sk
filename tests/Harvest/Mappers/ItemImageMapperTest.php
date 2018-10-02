<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\ItemImageMapper;
use Tests\TestCase;

class ItemImageMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new ItemImageMapper();
        $row = [
            'img_url' => ['http://www.webumenia.sk/oai-pmh/getimage/SVK:SNG.G_10044'],
            'iipimg_url' => ['/SNGBA/X100/SNG--G_23--1_2--_2013_02_20_--L2_WEB.jp2'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'img_url' => 'http://www.webumenia.sk/oai-pmh/getimage/SVK:SNG.G_10044',
            'iipimg_url' => '/SNGBA/X100/SNG--G_23--1_2--_2013_02_20_--L2_WEB.jp2',
        ];
        $this->assertEquals($expected, $mapped);
    }
}