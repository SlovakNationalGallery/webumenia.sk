<?php

namespace App\Http\Controllers;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\AuthoritySearchRequest;
use App\Filter\Contracts\Filter;
use App\Filter\Forms\Types\AuthoritySearchRequestType;

class AuthorController extends AbstractSearchRequestController
{
    protected $searchRequestClass = AuthoritySearchRequest::class;

    protected $searchRequestFormClass = AuthoritySearchRequestType::class;

    protected $indexView = 'frontend.authors.index';

    protected $itemRepository;

    public function __construct(AuthorityRepository $repository, ItemRepository $itemRepository)
    {
        parent::__construct($repository);
        $this->itemRepository = $itemRepository;
    }

    public function getDetail($id)
    {
        /** @var Authority $author */
        $author = Authority::withCount('items')->find($id);

        if (!$author) {
            abort(404);
        }

        $htmlDescription = view('components.authority_description')
            ->with('author', $author)
            ->render();

        $description = strip_tags($htmlDescription);
        $description = preg_replace('/\s+/', ' ', $description);
        $description = preg_replace('/ ,/', ',', $description);
        $description = trim($description);

        $author->incrementViewCount();
        return view('autor', [
            'author' => $author,
            'description' => $description,
            'html_description' => $htmlDescription,
            'previewItems' => $author->previewItems,
        ]);
    }

    protected function getSuggestionsData()
    {
        return parent::getSuggestionsData()->each(function (Authority $authority) {
            $authority->name = formatName($authority->name);
            $authority->image = $authority->getImagePath(false, 70);
        });
    }

    protected function getIndexData()
    {
        $data = parent::getIndexData();
        $paginator = $data['paginator'];
        $authorityIds = $paginator->getCollection()->pluck('id');
        $itemCounts = $paginator->getCollection()->pluck('items_count');

        if ($authorityIds->isEmpty()) {
            return $data;
        }

        $authorities = Authority::query()
            ->whereIn('id', $authorityIds)
            ->with(['previewItems', 'translations'])
            ->orderByRaw('FIELD(id, ' . $authorityIds->join(',') . ')')
            ->get()
            ->map(function (Authority $authority, $index) use ($itemCounts) {
                // Use indexed items_count to speed things up
                $authority->items_count = $itemCounts[$index];
                return $authority;
            });

        $paginator->setCollection($authorities);

        return $data;
    }

    protected function generateTitle(Filter $filter)
    {
        $attributes = collect(['role', 'nationality', 'place', 'years', 'first_letter']);

        return $attributes
            ->filter(fn($attribute) => $filter->get($attribute) !== null)
            ->map(function ($attribute) use ($filter) {
                $value = $filter->get($attribute);

                if ($attribute === 'years') {
                    return trans('authority.filter.title_generator.' . $attribute, [
                        'from' => $value->getFrom(),
                        'to' => $value->getTo(),
                    ]);
                }

                return trans('authority.filter.title_generator.' . $attribute, ['value' => $value]);
            })
            ->implode(" \u{2022} ");
    }
}
