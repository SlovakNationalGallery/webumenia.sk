<?php

namespace Tests\Observers;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use Elasticsearch\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorityObserverTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexWhenSaved()
    {
        $repositoryMock = $this->createMock(AuthorityRepository::class);
        $this->app->instance(AuthorityRepository::class, $repositoryMock);

        $authority = Authority::factory()->make();

        $repositoryMock
            ->expects($this->once())
            ->method('indexAllLocales')
            ->with(
                $this->callback(function (Authority $fresh) use ($authority) {
                    return $authority->is($fresh);
                })
            );

        $authority->save();
    }

    public function testDeleteFromIndexWhenDeleted()
    {
        $repositoryMock = $this->createMock(AuthorityRepository::class);
        $this->app->instance(AuthorityRepository::class, $repositoryMock);

        $authority = Authority::factory()->create();

        $repositoryMock
            ->expects($this->once())
            ->method('deleteAllLocales')
            ->with($authority);

        $authority->delete();
    }

    public function testUpdateRelatedWhenAttached()
    {
        $authorityElasticsearchMock = $this->createMock(Client::class);
        $itemElasticsearchMock = $this->createMock(Client::class);
        $authorityRepository = new AuthorityRepository(['sk'], $authorityElasticsearchMock);
        $itemRepository = new ItemRepository(['sk'], $itemElasticsearchMock);
        $this->app->instance(AuthorityRepository::class, $authorityRepository);
        $this->app->instance(ItemRepository::class, $itemRepository);

        $authority = Authority::factory()->create();
        $item = Item::factory()->create();

        $itemElasticsearchMock
            ->expects($this->once())
            ->method('index')
            ->with(
                $this->callback(function (array $params) use ($authority) {
                    return 1 === count($params['body']['authority_id']) &&
                        $authority->id === $params['body']['authority_id'][0];
                })
            );

        $authorityElasticsearchMock
            ->expects($this->once())
            ->method('index')
            ->with(
                $this->callback(function (array $params) {
                    return 1 === $params['body']['items_count'];
                })
            );

        $authority->items()->attach($item);
    }

    public function testUpdateRelatedWhenDetached()
    {
        $authorityElasticsearchMock = $this->createMock(Client::class);
        $itemElasticsearchMock = $this->createMock(Client::class);
        $repository = new AuthorityRepository(['sk'], $authorityElasticsearchMock);
        $itemRepository = new ItemRepository(['sk'], $itemElasticsearchMock);
        $this->app->instance(AuthorityRepository::class, $repository);
        $this->app->instance(ItemRepository::class, $itemRepository);

        $authority = Authority::factory()->create();
        $item = Item::factory()->create();
        $item->authorities()->attach($authority);

        $itemElasticsearchMock
            ->expects($this->once())
            ->method('index')
            ->with(
                $this->callback(function (array $params) {
                    return 0 === count($params['body']['authority_id']);
                })
            );

        $authorityElasticsearchMock
            ->expects($this->once())
            ->method('index')
            ->with(
                $this->callback(function (array $params) {
                    return 0 === $params['body']['items_count'];
                })
            );

        $authority->items()->detach($item);
    }

    public function testUpdateRelatedWhenSynced()
    {
        $authorityElasticsearchMock = $this->createMock(Client::class);
        $itemElasticsearchMock = $this->createMock(Client::class);
        $repository = new AuthorityRepository(['sk'], $authorityElasticsearchMock);
        $itemRepository = new ItemRepository(['sk'], $itemElasticsearchMock);
        $this->app->instance(AuthorityRepository::class, $repository);
        $this->app->instance(ItemRepository::class, $itemRepository);

        $item0 = Item::factory()->create();
        $item1 = Item::factory()->create();
        $authority = Authority::factory()->create();
        $authority->items()->attach($item0);

        $itemElasticsearchMock->expects($this->atLeast(2))->method('index');
        $authorityElasticsearchMock->expects($this->atLeastOnce())->method('index');

        $authority->items()->sync([$item1->id]);
    }
}
