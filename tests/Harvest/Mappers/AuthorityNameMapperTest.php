<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\AuthorityNameMapper;
use Tests\TestCase;

class AuthorityNameMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new AuthorityNameMapper();
        $row = [
            'name' => ['Blühová, Irena'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'name' => 'Blühová, Irena',
            'prefered' => false,
        ];
        $this->assertEquals($expected, $mapped);
    }
}