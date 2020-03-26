<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\AuthorityItemMapper;
use Tests\TestCase;

class AuthorityItemMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new AuthorityItemMapper();
        $row = [
            'role' => ['autor/author'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'role' => 'autor/author',
        ];
        $this->assertEquals($expected, $mapped);
    }
}