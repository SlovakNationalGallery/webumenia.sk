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
            'type' => 'person',
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
            'roles:en' => ['photographer'],
            'type_organization:en' => 'Zbierkotvorná galéria',
            'biography:en' => '',
            'birth_place:en' => null,
            'death_place:en' => null,
            'roles:cs' => [null],
            'type_organization:cs' => 'Zbierkotvorná galéria',
            'biography:cs' => '',
            'birth_place:cs' => null,
            'death_place:cs' => null,
        ];
        $this->assertEquals($expected, $mapped);
    }

    public function testMapNotDeadYet()
    {
        $mapper = new AuthorityMapper();

        $row = $this->fakeRow();
        $row['death_date'] = [''];

        $mapped = $mapper->map($row);

        $this->assertSame(null, $mapped['death_year']);
        $this->assertSame('', $mapped['death_date']);
    }

    protected function fakeRow()
    {
        return [
            'id' => [$this->faker->word],
            'identifier' => [$this->faker->randomNumber],
            'birth_place' => [$this->faker->city],
            'death_place' => [$this->faker->city],
            'type_organization' => [$this->faker->word],
            'biography' => [$this->faker->sentence],
            'type' => [$this->faker->word],
            'name' => [$this->faker->name],
            'sex' => [$this->faker->word],
            'birth_date' => [$this->faker->dateTime->format('d.m.Y')],
            'death_date' => [$this->faker->dateTime->format('d.m.Y')],
            'roles' => [$this->faker->word],
        ];
    }
}
