<?php

namespace Tests\Elasticsearch\Repositories;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\ItemSearchRequest;
use App\Item;

class ItemRepositoryTest extends TestCase
{
    /** @var ItemRepository */
    protected $repository;

    protected $repositoryClass = ItemRepository::class;

    public function testCount()
    {
        factory(Item::class, 5)->make([
            'tagged' => collect(),
            'images' => collect(),
            'authorities' => collect(),
            'updated_at' => new \DateTime(),
            'created_at' => new \DateTime(),
        ])->each(function (Item $item) {
            $this->repository->index($item);
        });
        $this->repository->refreshIndex();

        $count = $this->repository->count();
        $this->assertEquals(5, $count);
    }

    public function testSearch()
    {
        factory(Item::class, 5)->make([
            'tagged' => collect(),
            'images' => collect(),
            'authorities' => collect(),
            'updated_at' => new \DateTime(),
            'created_at' => new \DateTime(),
        ])->each(function (Item $item) {
            $this->repository->index($item);
        });
        $this->repository->refreshIndex();

        $response = $this->repository->search(new ItemSearchRequest());
        $this->assertEquals(5, $response->getTotal());
    }

    public function testSimilar()
    {
        /** @var Item[] $items */
        $items = factory(Item::class, 5)->make([
            'tagged' => collect(),
            'images' => collect(),
            'authorities' => collect(),
            'updated_at' => new \DateTime(),
            'created_at' => new \DateTime(),
        ]);
        $items[0]->title = 'testing title one';
        $items[1]->title = 'testing title two';
        foreach ($items as $item) {
            $this->repository->index($item);
        }
        $this->repository->refreshIndex();

        $mostSimilar  = $this->repository
            ->getSimilar(1, $items[0])
            ->getCollection()
            ->first();
        $this->assertTrue($items[1]->is($mostSimilar));
    }

    public function testSimilarByColor()
    {
        /** @var Item[] $items */
        $items = factory(Item::class, 2)->make([
            'tagged' => collect(),
            'images' => collect(),
            'authorities' => collect(),
            'updated_at' => new \DateTime(),
            'created_at' => new \DateTime(),
        ]);

        $items[0]->colors = ['#ff0000' => 1];
        $items[1]->colors = ['#fe0000' => 1];

        foreach ($items as $item) {
            $this->repository->index($item);
        }
        $this->repository->refreshIndex();

        $mostSimilar = $this->repository
            ->getSimilarByColor(2, $items[0])
            ->getCollection()
            ->get(0);
        $this->assertTrue($items[1]->is($mostSimilar));
    }
}