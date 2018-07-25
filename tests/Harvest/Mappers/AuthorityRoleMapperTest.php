<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\AuthorityRoleMapper;
use Tests\TestCase;

class AuthorityRoleMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new AuthorityRoleMapper();
        $row = [
            'role' => ['fotograf/photographer'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'role' => 'fotograf',
        ];
        $this->assertEquals($expected, $mapped);
    }
}