<?php

namespace App\Http\Controllers;

use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\Contracts\Filter;
use App\Filter\Forms\Types\ItemSearchRequestType;
use App\Filter\ItemFilter;
use App\Filter\ItemSearchRequest;
use App\Item;

class CatalogController extends AbstractSearchRequestController
{
    protected $searchRequestFormClass = ItemSearchRequestType::class;

    protected $searchRequestClass = ItemSearchRequest::class;

    protected $indexView = 'frontend.catalog.index';

    public function __construct(ItemRepository $repository)
    {
        parent::__construct($repository);
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
        $filter = (new ItemFilter())->setHasImage(true)->setHasIip(true);
        $item = $this->repository
            ->getRandom(1, $filter)
            ->getCollection()
            ->first();

        return response()->json($item);
    }

    protected function generateTitle(Filter $filter)
    {
        $attributes = collect([
            'search',
            'author',
            'work_type',
            'tag',
            'gallery',
            'credit',
            'topic',
            'medium',
            'technique',
            'has_image',
            'has_iip',
            'is_free',
            'related_work',
            'years',
        ]);

        return $attributes
            ->filter(fn($attribute) => $filter->get($attribute) !== null)
            ->map(function ($attribute) use ($filter) {
                $value = $filter->get($attribute);

                if ($attribute === 'author') {
                    $value = formatName($value);
                }

                if ($attribute === 'years') {
                    return trans('item.filter.title_generator.' . $attribute, [
                        'from' => $value->getFrom(),
                        'to' => $value->getTo(),
                    ]);
                }

                return trans('item.filter.title_generator.' . $attribute, ['value' => $value]);
            })
            ->implode(" \u{2022} ");
    }
}
