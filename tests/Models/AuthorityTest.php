<?php

namespace Tests\Models;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use Elasticsearch\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class AuthorityTest extends TestCase
{
    use RefreshDatabase;

    public function testEmptyRoles()
    {
        /** @var Client|MockObject $client */
        $client = $this->createMock(Client::class);
        $repository = new AuthorityRepository(['sk'], $client);

        $authority = Authority::factory()->create();

        $client
            ->expects($this->once())
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

        $authority = Authority::factory()->create(['roles' => 'foo']);

        $client
            ->expects($this->once())
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

        $authority = Authority::factory()->create(['roles' => ['foo', 'bar']]);

        $client
            ->expects($this->once())
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

    public function testRolesFormattedWorksForAllLocales()
    {
        $authority = Authority::factory()->create(['sex' => 'female']);
        $authority->translateOrNew('sk')->roles = ['autor', 'dizajnér'];
        $authority->translateOrNew('en')->roles = ['author', null];
        $authority->translateOrNew('cs')->roles = [null, null];
        $authority->save();

        App::setLocale('sk');
        $this->assertEquals(['autor', 'dizajnérka'], $authority->rolesFormatted->toArray());

        App::setLocale('en');
        $this->assertEquals(['author'], $authority->rolesFormatted->toArray());

        App::setLocale('cs');
        $this->assertEquals(['autor'], $authority->rolesFormatted->toArray());
    }
}
