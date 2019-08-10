<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\AuthorityEventMapper;
use Tests\TestCase;

class AuthorityEventMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new AuthorityEventMapper();
        $row = [
            'id' => [],
            'event' => ['štúdium/study'],
            'place' => ['Berlín'],
            'start_date' => ['1931'],
            'end_date' => ['1932'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'event' => 'štúdium',
            'place' => 'Berlín',
            'start_date' => '1931',
            'end_date' => '1932',
            'prefered' => '',
        ];
        $this->assertEquals($expected, $mapped);
    }
}