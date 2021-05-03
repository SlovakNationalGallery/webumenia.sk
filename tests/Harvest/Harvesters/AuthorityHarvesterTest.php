<?php

namespace Tests\Harvest\Harvesters;

use App\Harvest\Factories\EndpointFactory;
use App\Harvest\Harvesters\AuthorityHarvester;
use App\Harvest\Importers\AuthorityImporter;
use App\Harvest\Mappers\AuthorityEventMapper;
use App\Harvest\Mappers\AuthorityMapper;
use App\Harvest\Mappers\AuthorityNameMapper;
use App\Harvest\Mappers\AuthorityNationalityMapper;
use App\Harvest\Mappers\AuthorityRelationshipMapper;
use App\Harvest\Mappers\NationalityMapper;
use App\Harvest\Mappers\RelatedAuthorityMapper;
use App\Harvest\Repositories\AuthorityRepository;
use App\SpiceHarvesterHarvest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorityHarvesterTest extends TestCase
{
    use RefreshDatabase;

    public function testTryHarvestNoRows()
    {
        $repositoryMock = $this->createMock(AuthorityRepository::class, [], [
            $this->createMock(EndpointFactory::class)
        ]);
        $importerMock = $this->createMock(AuthorityImporter::class, [], [
            $this->createMock(AuthorityMapper::class),
            $this->createMock(AuthorityEventMapper::class),
            $this->createMock(AuthorityNameMapper::class),
            $this->createMock(AuthorityNationalityMapper::class),
            $this->createMock(AuthorityRelationshipMapper::class),
            $this->createMock(NationalityMapper::class),
            $this->createMock(RelatedAuthorityMapper::class),
        ]);

        $repositoryMock->expects($this->once())
            ->method('getAll')
            ->willReturn([]);

        $harvester = new AuthorityHarvester($repositoryMock, $importerMock);

        $harvest = factory(SpiceHarvesterHarvest::class)->make([
            'type' => 'author',
            'status' => SpiceHarvesterHarvest::STATUS_QUEUED
        ]);
        $harvester->harvest($harvest);

        $this->assertEquals(SpiceHarvesterHarvest::STATUS_COMPLETED, $harvest->status);
    }
}
