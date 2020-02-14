<?php

namespace App\Http\Controllers;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\Forms\Types\ItemSearchRequestType;
use App\Filter\Generators\ItemTitleGenerator;
use App\Filter\ItemFilter;
use App\Filter\ItemSearchRequest;
use App\Item;

class CatalogController extends AbstractSearchRequestController
{
    protected $searchRequestFormClass = ItemSearchRequestType::class;

    protected $searchRequestClass = ItemSearchRequest::class;

    protected $indexView = 'frontend.catalog.index';

    public function __construct(
        ItemRepository $repository,
        ItemTitleGenerator $titleGenerator
    ) {
        parent::__construct($repository, $titleGenerator);
    }

    public function getSuggestionsData()
    {
        return parent::getSuggestionsData()->each(function (Item $item) {
            $item->image = $item->getImagePath(false, 70);
            $item->author = implode(', ', array_values($item->authors));
        });
    }

    public function getRandom()
    {
        $filter = (new ItemFilter)
            ->setHasImage(true)
            ->setHasIip(true);
        $item = $this->repository
            ->getRandom(1, $filter)
            ->getCollection()
            ->first();

        return response()->json($item);
    }
}
