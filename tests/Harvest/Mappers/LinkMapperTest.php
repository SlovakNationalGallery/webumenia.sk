<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\LinkMapper;
use Tests\TestCase;

class LinkMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new LinkMapper();
        $row = [
            'url' => ['http://example.org/'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'url' => 'http://example.org/',
            'label' => 'example.org',
        ];
        $this->assertEquals($expected, $mapped);
    }
}