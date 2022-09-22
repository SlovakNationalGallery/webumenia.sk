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
        Item::factory()
            ->count(5)
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
        Item::factory()
            ->count(5)
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
        $items = Item::factory()
            ->count(5)
            ->make();
        $items[0]->title = 'testing title one';
        $items[1]->title = 'testing title two';
        $items[1]->has_image = true;

        foreach ($items as $item) {
            $this->repository->index($item);
        }
        $this->repository->refreshIndex();

        $mostSimilar = $this->repository
            ->getSimilar(1, $items[0])
            ->getCollection()
            ->first();
        $this->assertTrue($items[1]->is($mostSimilar));
    }

    public function testSimilarByColor()
    {
        /** @var Item[] $items */
        $item = Item::factory()->make([
            'colors' => ['#ff0000' => 1],
        ]);

        $similar_item = Item::factory()->make([
            'colors' => ['#fe0000' => 1],
        ]);

        $dissimilar_item = Item::factory()->make([
            'colors' => ['#0000ff' => 1],
        ]);

        foreach ([$item, $dissimilar_item, $similar_item] as $index_item) {
            $this->repository->index($index_item);
        }
        $this->repository->refreshIndex();

        $similar_by_color = $this->repository->getSimilarByColor(2, $item)->getCollection();

        $this->assertTrue($similar_by_color->first()->is($similar_item));
        $this->assertFalse($similar_by_color->contains($dissimilar_item));
    }
}
