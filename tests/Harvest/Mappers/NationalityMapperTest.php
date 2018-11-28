<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\NationalityMapper;
use Tests\TestCase;

class NationalityMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new NationalityMapper();
        $row = [
            'id' => ['urn:svk:psi:per:sng:0000012277'],
            'code' => ['Slovensko'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 12277,
            'code' => 'Slovensko',
        ];
        $this->assertEquals($expected, $mapped);
    }
}