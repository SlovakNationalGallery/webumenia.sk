<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\AuthorityNationalityMapper;
use Tests\TestCase;

class AuthorityNationalityMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new AuthorityNationalityMapper();
        $row = [];

        $mapped = $mapper->map($row);

        $expected = [
            'prefered' => false,
        ];
        $this->assertEquals($expected, $mapped);
    }
}