<?php

namespace Tests\Elasticsearch\Repositories;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\ItemSearchRequest;
use App\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var ItemRepository */
    protected $repository;

    protected $repositoryClass = ItemRepository::class;

    public function testCount()
    {
        Item::factory()
            ->webumeniaFrontend()
            ->count(5)
            ->create();
        $this->repository->refreshIndex();

        $count = $this->repository->count();
        $this->assertEquals(5, $count);
    }

    public function testSearch()
    {
        Item::factory()
            ->webumeniaFrontend()
            ->count(5)
            ->create();
        $this->repository->refreshIndex();

        $response = $this->repository->search(new ItemSearchRequest());
        $this->assertEquals(5, $response->getTotal());
    }

    public function testSimilar()
    {
        $item = Item::factory()
            ->webumeniaFrontend()
            ->create([
                'title' => 'testing title one'
            ]);

        $expectedItem = Item::factory()
            ->webumeniaFrontend()
            ->create([
                'title' => 'testing title two',
                'has_image' => true,
            ]);

        $otherItems = Item::factory()
            ->webumeniaFrontend()
            ->count(3)
            ->create();

        $this->repository->refreshIndex();

        $mostSimilar = $this->repository
            ->getSimilar(1, $item)
            ->getCollection()
            ->first();
        $this->assertEquals($expectedItem->id, $mostSimilar->id);
    }

    public function testSimilarByColor()
    {
        /** @var Item[] $items */
        $item = Item::factory()
            ->webumeniaFrontend()
            ->create([
                'colors' => ['#ff0000' => 1],
            ]);

        $similar_item = Item::factory()
            ->webumeniaFrontend()
            ->create([
                'colors' => ['#fe0000' => 1],
            ]);

        $dissimilar_item = Item::factory()
            ->webumeniaFrontend()
            ->create([
                'colors' => ['#0000ff' => 1],
            ]);

        $this->repository->refreshIndex();

        $similar_by_color = $this->repository->getSimilarByColor(2, $item)->getCollection();

        $this->assertEquals($similar_item->id, $similar_by_color->first()->id);
        $this->assertFalse($similar_by_color->contains($dissimilar_item));
    }
}
