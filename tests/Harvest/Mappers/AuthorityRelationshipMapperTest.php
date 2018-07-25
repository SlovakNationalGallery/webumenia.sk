<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\AuthorityRelationshipMapper;
use Tests\TestCase;

class AuthorityRelationshipMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new AuthorityRelationshipMapper();
        $row = [
            'type' => ['študent (osoba - inštitúcia)/student at (person to institution)'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'type' => 'študent (osoba - inštitúcia)',
        ];
        $this->assertEquals($expected, $mapped);
    }
}