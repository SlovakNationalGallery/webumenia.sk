<?php

namespace Tests\Models;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use Elasticsearch\Client;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class AuthorityTest extends TestCase
{
    use DatabaseMigrations;

    public function testEmptyRoles()
    {
        /** @var Client|MockObject $client */
        $client = $this->createMock(Client::class);
        $repository = new AuthorityRepository(['sk'], $client);

        $authority = factory(Authority::class)->create();

        $client->expects($this->once())
            ->method('get')
            ->with([
                'index' => $repository->getLocalizedIndexName('sk'),
                'id' => $authority->id,
            ])
            ->willReturn([
                '_source' => $authority->getIndexedData('sk'),
            ]);

        $authority = $repository->get($authority->id);
        $this->assertEquals([], $authority->roles);
    }

    public function testSingleRole()
    {
        /** @var Client|MockObject $client */
        $client = $this->createMock(Client::class);
        $repository = new AuthorityRepository(['sk'], $client);

        $authority = factory(Authority::class)->create(['roles' => 'foo']);

        $client->expects($this->once())
            ->method('get')
            ->with([
                'index' => $repository->getLocalizedIndexName('sk'),
                'id' => $authority->id,
            ])
            ->willReturn([
                '_source' => $authority->getIndexedData('sk'),
            ]);

        $authority = $repository->get($authority->id);
        $this->assertEquals(['foo'], $authority->roles);
    }

    public function testMultipleRoles()
    {
        /** @var Client|MockObject $client */
        $client = $this->createMock(Client::class);
        $repository = new AuthorityRepository(['sk'], $client);

        $authority = factory(Authority::class)->create(['roles' => ['foo', 'bar']]);

        $client->expects($this->once())
            ->method('get')
            ->with([
                'index' => $repository->getLocalizedIndexName('sk'),
                'id' => $authority->id,
            ])
            ->willReturn([
                '_source' => $authority->getIndexedData('sk'),
            ]);

        $authority = $repository->get($authority->id);
        $this->assertEquals(['foo', 'bar'], $authority->roles);
    }
}