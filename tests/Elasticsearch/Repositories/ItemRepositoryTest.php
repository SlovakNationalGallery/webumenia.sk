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
        factory(Item::class, 5)
            ->make()
            ->each(function (Item $item) {
                $this->repository->index($item);
            });
        $this->repository->refreshIndex();

        $count = $this->repository->count();
        $this->assertEquals(5, $count);
    }

    public function testSearch()
    {
        factory(Item::class, 5)
            ->make()
            ->each(function (Item $item) {
                $this->repository->index($item);
            });
        $this->repository->refreshIndex();

        $response = $this->repository->search(new ItemSearchRequest());
        $this->assertEquals(5, $response->getTotal());
    }

    public function testSimilar()
    {
        /** @var Item[] $items */
        $items = factory(Item::class, 5)->make();
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
        $item = factory(Item::class)->make([
            'colors' => ['#ff0000' => 1]
        ]);

        $similar_item = factory(Item::class)->make([
            'colors' => ['#fe0000' => 1]
        ]);

        $dissimilar_item = factory(Item::class)->make([
            'colors' => ['#0000ff' => 1]
        ]);

        foreach ([$item, $dissimilar_item, $similar_item] as $index_item) {
            $this->repository->index($index_item);
        }
        $this->repository->refreshIndex();

        $similar_by_color = $this->repository
            ->getSimilarByColor(2, $item)
            ->getCollection();

        $this->assertTrue($similar_by_color->first()->is($similar_item));
        $this->assertFalse($similar_by_color->contains($dissimilar_item));
    }
}
