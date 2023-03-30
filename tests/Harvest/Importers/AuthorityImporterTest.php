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
use App\Harvest\Mappers\NationalityMapper;
use App\Harvest\Mappers\RelatedAuthorityMapper;
use App\Harvest\Progress;
use App\Nationality;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Harvest\FakeRecordFactory;
use Tests\TestCase;

class AuthorityImporterTest extends TestCase
{
    private AuthorityImporter $importer;

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->importer = new AuthorityImporter(
            app(AuthorityMapper::class),
            app(AuthorityEventMapper::class),
            app(AuthorityNameMapper::class),
            app(AuthorityNationalityMapper::class),
            app(AuthorityRelationshipMapper::class),
            app(NationalityMapper::class),
            app(RelatedAuthorityMapper::class)
        );
    }

    public function testExistingButNotRelatedYet()
    {
        Nationality::factory()->create([
            'id' => 12277,
            'code' => 'Slovensko',
        ]);

        $row = FakeRecordFactory::buildAuthority([
            'nationalities' => [
                [
                    'id' => ['urn:svk:psi:per:sng:0000012277'],
                    'code' => ['Slovensko'],
                ],
            ],
        ]);
        $authority = $this->importer->import($row, new Progress());
        $this->assertEquals(1, $authority->nationalities->count());
    }

    public function testRelatedButNotExisting()
    {
        AuthorityRelationship::factory()->create([
            'authority_id' => 954,
            'related_authority_id' => 1000162,
            'type' => '',
        ]);

        $row = FakeRecordFactory::buildAuthority([
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
        ]);

        $authority = $this->importer->import($row, new Progress());
        $this->assertEquals(0, $authority->relationships->count());
    }
}
