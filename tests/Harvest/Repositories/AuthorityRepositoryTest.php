<?php

namespace Tests\Harvest\Repositories;

use App\Harvest\Factories\EndpointFactory;
use App\Harvest\Repositories\AuthorityRepository;
use App\SpiceHarvesterHarvest;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Phpoaipmh\HttpAdapter\HttpAdapterInterface;
use Tests\TestCase;

class AuthorityRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testRows() {
        $endpointFactoryMock = $this->getMock(EndpointFactory::class, ['createHttpAdapter']);
        $httpAdapterMock = $this->getMock(HttpAdapterInterface::class);
        $authorityXml = file_get_contents(__DIR__ . '/authority.xml');
        $httpAdapterMock->method('request')->willReturn($authorityXml);
        $endpointFactoryMock->method('createHttpAdapter')->willReturn($httpAdapterMock);

        $itemRepository = new AuthorityRepository($endpointFactoryMock);

        $harvest = factory(SpiceHarvesterHarvest::class)->make(['base_url' => true]);
        $rows = $itemRepository->getRows($harvest);

        $rows = iterator_to_array($rows);
        $this->assertCount(1, $rows);
        $expected = [
            'identifier' => ['954'],
            'birth_place' => ['Považská Bystrica'],
            'death_place' => ['Bratislava'],
            'type_organization' => ['Zbierkotvorná galéria'],
            'biography' => ['AUTOR: Blühová Irena (ZNÁMY)'],
            'id' => ['urn:svk:psi:per:sng:0000000954'],
            'type' => ['Person'],
            'name' => ['Blühová, Irena'],
            'sex' => ['Female'],
            'birth_date' => ['02.03.1904'],
            'death_date' => ['30.11.1991'],
            'roles' => ['fotograf/photographer'],
            'names' => [
                [
                    'name' => ['Blühová, Irena'],
                ],
            ],
            'nationalities' => [
                [
                    'id' => ['urn:svk:psi:per:sng:0000012277'],
                    'code' => ['Slovensko'],
                ],
            ],
            'events' => [
                [
                    'id' => ['1000166'],
                    'event' => ['štúdium/study'],
                    'place' => ['Berlín'],
                    'start_date' => ['1931'],
                    'end_date' => ['1932'],
                ],
            ],
            'relationships' => [
                [
                    'type' => ['študent (osoba - inštitúcia)/student at (person to institution)'],
                    'related_authority_id' => ['urn:svk:psi:per:sng:0001000162'],
                ],
                [
                    'type' => ['člen/member'],
                    'related_authority_id' => ['urn:svk:psi:per:sng:0001000168'],
                ],
                [
                    'type' => ['partner/partner'],
                    'related_authority_id' => ['urn:svk:psi:per:sng:0000011680'],
                ],
            ],
            'datestamp' => ['2015-02-16T22:55:34Z'],
        ];
        $this->assertEquals($expected, $rows[0]);
    }
}