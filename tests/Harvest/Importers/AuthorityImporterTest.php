<?php

namespace Tests\Harvest\Importers;

use App\Authority;
use App\AuthorityRelationship;
use App\Harvest\Importers\AuthorityImporter;
use App\Harvest\Mappers\AuthorityEventMapper;
use App\Harvest\Mappers\AuthorityMapper;
use App\Harvest\Mappers\AuthorityNameMapper;
use App\Harvest\Mappers\AuthorityNationalityMapper;
use App\Harvest\Mappers\AuthorityRelationshipMapper;
use App\Harvest\Mappers\LinkMapper;
use App\Harvest\Mappers\NationalityMapper;
use App\Harvest\Mappers\RelatedAuthorityMapper;
use App\Harvest\Progress;
use App\Link;
use App\Nationality;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthorityImporterTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testUpdateRelated() {
        factory(Authority::class)->create(['id' => 1000162]);
        factory(Authority::class)->create(['id' => 1000168]);
        factory(Authority::class)->create(['id' => 11680]);

        factory(Link::class)->create([
            'url' => 'http://example.org/',
            'label' => '',
            'linkable_id' => 954,
            'linkable_type' => Authority::class,
        ]);

        $row = $this->getData();
        $importer = $this->initImporter($row);

        $authority = $importer->import($row, $result = new Progress());
        $this->assertEquals(1, $authority->links->count());
        $this->assertEquals('example.org', $authority->links[0]->label);
    }

    public function testExistingButNotRelatedYet() {
        factory(Nationality::class)->create([
            'id' => 12277,
            'code' => 'Slovensko',
        ]);

        $row = $this->getData();
        $importer = $this->initImporter($row);

        $authority = $importer->import($row, $result = new Progress());
        $this->assertEquals(1, $authority->nationalities->count());
    }

    public function testRelatedButNotExisting() {
        factory(AuthorityRelationship::class)->create([
            'authority_id' => 954,
            'related_authority_id' => 1000162,
            'type' => '',
        ]);

        $row = $this->getData();
        $importer = $this->initImporter($row);

        $authority = $importer->import($row, $result = new Progress());
        $this->assertEquals(0, $authority->relationships->count());
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
            'links' => [
                [
                    'url' => ['http://example.org/'],
                ]
            ],
        ];
    }

    protected function initImporter(array $row) {
        $importer = new AuthorityImporter(
            $authorityMapperMock = $this->createMock(AuthorityMapper::class),
            $authorityEventMapperMock = $this->createMock(AuthorityEventMapper::class),
            $authorityNameMapperMock = $this->createMock(AuthorityNameMapper::class),
            $authorityNationalityMapperMock = $this->createMock(AuthorityNationalityMapper::class),
            $authorityRelationshipMapperMock = $this->createMock(AuthorityRelationshipMapper::class),
            $linkMapperMock = $this->createMock(LinkMapper::class),
            $nationalityMapperMock = $this->createMock(NationalityMapper::class),
            $relatedAuthorityMapperMock = $this->createMock(RelatedAuthorityMapper::class)
        );

        $authorityMapperMock
            ->expects($this->once())
            ->method('map')
            ->with($row)
            ->willReturn([
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
