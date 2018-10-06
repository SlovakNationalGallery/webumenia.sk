<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\RelatedAuthorityMapper;
use Tests\TestCase;

class RelatedAuthorityMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new RelatedAuthorityMapper();
        $row = [
            'related_authority_id' => ['urn:svk:psi:per:sng:0001000162'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 1000162
        ];
        $this->assertEquals($expected, $mapped);
    }
}