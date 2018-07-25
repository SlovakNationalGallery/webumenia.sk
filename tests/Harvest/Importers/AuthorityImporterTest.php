<?php

namespace Tests\Harvest\Importers;

use App\Authority;
use App\AuthorityRelationship;
use App\Harvest\Importers\AuthorityImporter;
use App\Harvest\Importers\Result;
use App\Harvest\Mappers\AuthorityEventMapper;
use App\Harvest\Mappers\AuthorityMapper;
use App\Harvest\Mappers\AuthorityNameMapper;
use App\Harvest\Mappers\AuthorityNationalityMapper;
use App\Harvest\Mappers\AuthorityRelationshipMapper;
use App\Harvest\Mappers\AuthorityRoleMapper;
use App\Harvest\Mappers\LinkMapper;
use App\Harvest\Mappers\NationalityMapper;
use App\Harvest\Mappers\RelatedAuthorityMapper;
use App\Link;
use App\Nationality;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthorityImporterTest extends TestCase
{
    use DatabaseMigrations;

    public function testUpdateRelated() {
        $this->createModel(Authority::class, ['id' => 1000162]);
        $this->createModel(Authority::class, ['id' => 1000168]);
        $this->createModel(Authority::class, ['id' => 11680]);

        $this->createModel(Link::class, [
            'url' => 'http://example.org/',
            'label' => '',
            'linkable_id' => 954,
            'linkable_type' => Authority::class,
        ]);

        $row = $this->getData();
        $importer = $this->initImporter($row);

        $importer->import($row, $result = new Result());

        $links = Link::all();
        $this->assertEquals(1, $links->count());
        $this->assertEquals('example.org', $links->first()->label);
    }

    public function testInsertRelated() {
        $row = $this->getData();
        $importer = $this->initImporter($row);

        $importer->import($row, $result = new Result());
    }

    public function testExistingButNotRelatedYet() {
        $this->createModel(Nationality::class, [
            'id' => 12277,
            'code' => 'Slovensko',
        ]);

        $row = $this->getData();
        $importer = $this->initImporter($row);

        $importer->import($row, $result = new Result());

        $item = Authority::first();
        $this->assertCount(1, $item->nationalities);
    }

    public function testRelatedButNotExisting() {
        $this->createModel(AuthorityRelationship::class, [
            'authority_id' => 954,
            'related_authority_id' => 1000162,
            'type' => '',
        ]);

        $row = $this->getData();
        $importer = $this->initImporter($row);

        $importer->import($row, $result = new Result());
    }

    protected function getData() {
        return [
            'identifier' => ['954'],
            'datestamp' => ['2015-02-16T22:55:34Z'],
            'birth_place' => ['Považská Bystrica'],
            'death_place' => ['Bratislava'],
            'type_organization' => ['Zbierkotvorná galéria'],
            'biography' => ['AUTOR: Blühová Irena (ZNÁMY) http://example.org/'],
            'id' => ['urn:svk:psi:per:sng:0000000954'],
            'type' => ['Person'],
            'name' => ['Blühová, Irena'],
            'sex' => ['Female'],
            'birth_date' => ['02.03.1904'],
            'death_date' => ['30.11.1991'],
            'roles' => [
                [
                    'role' => ['fotograf/photographer'],
                ],
            ],
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
            'links' => [
                [
                    'url' => ['http://example.org/'],
                ]
            ],
        ];
    }

    protected function initImporter(array $row) {
        $importer = new AuthorityImporter(
            $authorityMapperMock = $this->getMock(AuthorityMapper::class),
            $authorityEventMapperMock = $this->getMock(AuthorityEventMapper::class),
            $authorityNameMapperMock = $this->getMock(AuthorityNameMapper::class),
            $authorityNationalityMapperMock = $this->getMock(AuthorityNationalityMapper::class),
            $authorityRelationshipMapperMock = $this->getMock(AuthorityRelationshipMapper::class),
            $authorityRoleMapperMock = $this->getMock(AuthorityRoleMapper::class),
            $linkMapperMock = $this->getMock(LinkMapper::class),
            $nationalityMapperMock = $this->getMock(NationalityMapper::class),
            $relatedAuthorityMapperMock = $this->getMock(RelatedAuthorityMapper::class)
        );

        $authorityMapperMock
            ->expects($this->once())
            ->method('map')
            ->with($row)
            ->willReturn([
                'id' => 954,
                'type' => 'Person',
                'type_organization' => 'Zbierkotvorná galéria',
                'name' => 'Blühová, Irena',
                'sex' => 'Female',
                'biography' => '',
                'birth_place' => 'Považská Bystrica',
                'death_place' => 'Bratislava',
                'birth_date' => '02.03.1904',
                'death_date' => '30.11.1991',
            ]);
        $authorityEventMapperMock
            ->expects($this->exactly(1))
            ->method('map')
            ->withConsecutive([$row['events'][0]])
            ->willReturnOnConsecutiveCalls([
                'id' => '1000166',
                'event' => 'štúdium',
                'place' => 'Berlín',
                'start_date' => '1931',
                'end_date' => '1932',
                'prefered' => false,
            ]);
        $authorityNameMapperMock
            ->expects($this->exactly(1))
            ->method('map')
            ->withConsecutive([$row['names'][0]])
            ->willReturnOnConsecutiveCalls([
                'name' => 'Blühová, Irena',
                'prefered' => false,
            ]);
        $authorityNationalityMapperMock
            ->expects($this->exactly(1))
            ->method('map')
            ->withConsecutive([$row['nationalities'][0]])
            ->willReturnOnConsecutiveCalls(
                ['prefered' => false]
            );
        $authorityRelationshipMapperMock
            ->expects($this->exactly(3))
            ->method('map')
            ->withConsecutive(
                [$row['relationships'][0]],
                [$row['relationships'][1]],
                [$row['relationships'][2]]
            )
            ->willReturnOnConsecutiveCalls(
                ['type' => 'študent (osoba - inštitúcia)'],
                ['type' => 'člen'],
                ['type' => 'partner']
            );
        $authorityRoleMapperMock
            ->expects($this->exactly(1))
            ->method('map')
            ->withConsecutive([$row['roles'][0]])
            ->willReturnOnConsecutiveCalls([
                'role' => 'fotograf',
                'prefered' => false,
            ]);
        $linkMapperMock
            ->expects($this->exactly(1))
            ->method('map')
            ->withConsecutive([$row['links'][0]])
            ->willReturnOnConsecutiveCalls([
                'url' => 'http://example.org/',
                'label' => 'example.org',
            ]);
        $nationalityMapperMock
            ->expects($this->exactly(1))
            ->method('map')
            ->withConsecutive([$row['nationalities'][0]])
            ->willReturnOnConsecutiveCalls(
                [
                    'id' => 12277,
                    'code' => 'Slovensko',
                ]
            );
        $relatedAuthorityMapperMock
            ->expects($this->exactly(3))
            ->method('map')
            ->withConsecutive(
                [$row['relationships'][0]],
                [$row['relationships'][1]],
                [$row['relationships'][2]]
            )
            ->willReturnOnConsecutiveCalls(
                ['id' => 1000162],
                ['id' => 1000168],
                ['id' => 11680]
            );

        return $importer;
    }
}