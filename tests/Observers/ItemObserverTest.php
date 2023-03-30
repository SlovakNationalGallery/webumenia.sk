<?php

namespace Tests\Observers;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;
use Elasticsearch\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemObserverTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexWhenSaved()
    {
        $repositoryMock = $this->createMock(ItemRepository::class);
        $this->app->instance(ItemRepository::class, $repositoryMock);

        $item = Item::factory()->make();

        $repositoryMock
            ->expects($this->once())
            ->method('indexAllLocales')
            ->with(
                $this->callback(function (Item $fresh) use ($item) {
                    return $item->is($fresh);
                })
            );

        $item->save();
    }

    public function testDeleteFromIndexWhenDeleted()
    {
        $repositoryMock = $this->createMock(ItemRepository::class);
        $this->app->instance(ItemRepository::class, $repositoryMock);

        $item = Item::factory()->create();

        $repositoryMock
            ->expects($this->once())
            ->method('deleteAllLocales')
            ->with($item);

        $item->delete();
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

        $item->authorities()->attach($authority);
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

        $item->authorities()->detach($authority);
    }

    public function testUpdateRelatedWhenSynced()
    {
        $authorityElasticsearchMock = $this->createMock(Client::class);
        $itemElasticsearchMock = $this->createMock(Client::class);
        $repository = new AuthorityRepository(['sk'], $authorityElasticsearchMock);
        $itemRepository = new ItemRepository(['sk'], $itemElasticsearchMock);
        $this->app->instance(AuthorityRepository::class, $repository);
        $this->app->instance(ItemRepository::class, $itemRepository);

        $authority0 = Authority::factory()->create();
        $authority1 = Authority::factory()->create();
        $item = Item::factory()->create();
        $item->authorities()->attach($authority0);

        $itemElasticsearchMock->expects($this->atLeastOnce())->method('index');
        $authorityElasticsearchMock->expects($this->exactly(2))->method('index');

        $item->authorities()->sync([$authority1->id]);
    }
}
