<?php

namespace Tests\Harvest\Harvesters;

use App\Harvest\Factories\EndpointFactory;
use App\Harvest\Harvesters\ItemHarvester;
use App\Harvest\Importers\ItemImporter;
use App\Harvest\Mappers\AuthorityItemMapper;
use App\Harvest\Mappers\AuthorityMapper;
use App\Harvest\Mappers\CollectionItemMapper;
use App\Harvest\Mappers\ItemImageMapper;
use App\Harvest\Mappers\ItemMapper;
use App\Harvest\Repositories\ItemRepository;
use App\SpiceHarvesterHarvest;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ItemHarvesterTest extends TestCase
{
    use DatabaseMigrations;

    public function testTryHarvestNoRows()
    {
        $repositoryMock = $this->createMock(ItemRepository::class, [], [
            $this->createMock(EndpointFactory::class)
        ]);
        $importerMock = $this->createMock(ItemImporter::class, [], [
            $this->createMock(ItemMapper::class),
            $this->createMock(ItemImageMapper::class),
            $this->createMock(CollectionItemMapper::class),
            $this->createMock(AuthorityItemMapper::class),
            $this->createMock(AuthorityMapper::class),
        ]);

        $repositoryMock->expects($this->once())
            ->method('getRows')
            ->willReturn([]);

        $harvester = new ItemHarvester($repositoryMock, $importerMock);

        $harvest = factory(SpiceHarvesterHarvest::class)->make([
            'type' => 'item',
            'status' => SpiceHarvesterHarvest::STATUS_QUEUED
        ]);
        $harvester->tryHarvest($harvest);

        $this->assertEquals(SpiceHarvesterHarvest::STATUS_COMPLETED, $harvest->status);
    }
}
