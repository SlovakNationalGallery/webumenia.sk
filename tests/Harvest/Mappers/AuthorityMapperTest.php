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
            'roles' => ['fotograf/photographer'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 954,
            'type' => 'author',
            'name' => 'Blühová, Irena',
            'sex' => 'female',
            'birth_date' => '02.03.1904',
            'death_date' => '30.11.1991',
            'birth_year' => 1904,
            'death_year' => 1991,
            'roles:sk' => ['fotograf'],
            'type_organization:sk' => 'Zbierkotvorná galéria',
            'biography:sk' => '',
            'birth_place:sk' => 'Považská Bystrica',
            'death_place:sk' => 'Bratislava',
            // @TODO: other then slovak locales are temporary disabled in Harvester
            'roles:en' => ['photographer'],
            'type_organization:en' => 'Zbierkotvorná galéria',
            'biography:en' => '',
            'birth_place:en' => null,
            'death_place:en' => null,
            // 'roles:cs' => [null],
            // 'type_organization:cs' => 'Zbierkotvorná galéria',
            // 'biography:cs' => '',
            // 'birth_place:cs' => null,
            // 'death_place:cs' => null,
        ];
        $this->assertEquals($expected, $mapped);
    }
}