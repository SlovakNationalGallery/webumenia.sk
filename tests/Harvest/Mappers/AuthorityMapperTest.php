<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\AuthorityMapper;
use Tests\TestCase;

class AuthorityMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new AuthorityMapper();
        $row = [
            'id' => ['urn:svk:psi:per:sng:0000000954'],
            'identifier' => ['954'],
            'datestamp' => ['2015-02-16T22:55:34Z'],
            'birth_place' => ['Považská Bystrica'],
            'death_place' => ['Bratislava'],
            'type_organization' => ['Zbierkotvorná galéria'],
            'biography' => ['AUTOR: Blühová Irena (ZNÁMY)'],
            'type' => ['Person'],
            'name' => ['Blühová, Irena'],
            'sex' => ['Female'],
            'birth_date' => ['02.03.1904'],
            'death_date' => ['30.11.1991'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 954,
            'type' => 'person',
            'type_organization' => 'Zbierkotvorná galéria',
            'name' => 'Blühová, Irena',
            'sex' => 'female',
            'biography' => '',
            'birth_place' => 'Považská Bystrica',
            'birth_date' => '02.03.1904',
            'death_place' => 'Bratislava',
            'death_date' => '30.11.1991',
            'birth_year' => 1904,
            'death_year' => 1991,
        ];
        $this->assertEquals($expected, $mapped);
    }
}