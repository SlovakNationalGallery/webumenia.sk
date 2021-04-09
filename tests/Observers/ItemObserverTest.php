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

        $item = factory(Item::class)->make();

        $repositoryMock
            ->expects($this->once())
            ->method('indexAllLocales')
            ->with($this->callback(function (Item $fresh) use ($item) {
                return $item->is($fresh);
            }));

        $item->save();
    }

    public function testDeleteFromIndexWhenDeleted()
    {
        $repositoryMock = $this->createMock(ItemRepository::class);
        $this->app->instance(ItemRepository::class, $repositoryMock);

        $item = factory(Item::class)->create();

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

        $authority = factory(Authority::class)->create();
        $item = factory(Item::class)->create();

        $itemElasticsearchMock
            ->expects($this->once())
            ->method('index')
            ->with($this->callback(
                function (array $params) use ($authority) {
                    return 1 === count($params['body']['authority_id']) &&
                        $authority->id === $params['body']['authority_id'][0];
                }
            ));

        $authorityElasticsearchMock
            ->expects($this->once())
            ->method('index')
            ->with($this->callback(
                function (array $params) {
                    return 1 === $params['body']['items_count'];
                }
            ));

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

        $authority = factory(Authority::class)->create();
        $item = factory(Item::class)->create();
        $item->authorities()->attach($authority);

        $itemElasticsearchMock
            ->expects($this->once())
            ->method('index')
            ->with($this->callback(
                function (array $params) {
                    return 0 === count($params['body']['authority_id']);
                }
            ));

        $authorityElasticsearchMock
            ->expects($this->once())
            ->method('index')
            ->with($this->callback(
                function (array $params) {
                    return 0 === $params['body']['items_count'];
                }
            ));

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

        $authority0 = factory(Authority::class)->create();
        $authority1 = factory(Authority::class)->create();
        $item = factory(Item::class)->create();
        $item->authorities()->attach($authority0);

        $itemElasticsearchMock
            ->expects($this->at(1))
            ->method('index')
            ->with($this->callback(
                function (array $params) use ($authority1) {
                    return 1 === count($params['body']['authority_id']) &&
                        $authority1->id === $params['body']['authority_id'][0];
                }
            ));

        $authorityElasticsearchMock
            ->expects($this->at(0))
            ->method('index')
            ->with($this->callback(
                function (array $params) use ($authority0) {
                    return $authority0->id === $params['body']['id'] &&
                        0 === $params['body']['items_count'];
                }
            ));
        $authorityElasticsearchMock
            ->expects($this->at(1))
            ->method('index')
            ->with($this->callback(
                function (array $params) use ($authority1) {
                    return $authority1->id === $params['body']['id'] &&
                        1 === $params['body']['items_count'];
                }
            ));

        $item->authorities()->sync([$authority1->id]);
    }
}
