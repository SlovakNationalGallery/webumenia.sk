<?php

namespace App\Http\Controllers;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Filter\AuthoritySearchRequest;
use App\Filter\Forms\Types\AuthoritySearchRequestType;
use App\Filter\Generators\AuthorityTitleGenerator;
use Illuminate\Pagination\AbstractPaginator;

class AuthorController extends AbstractSearchRequestController
{
    protected $searchRequestClass = AuthoritySearchRequest::class;

    protected $searchRequestFormClass = AuthoritySearchRequestType::class;

    protected $indexView = 'frontend.authors.index';

    protected $itemRepository;

    public function __construct(
        AuthorityRepository $repository,
        AuthorityTitleGenerator $titleGenerator,
        ItemRepository $itemRepository
    ) {
        parent::__construct($repository, $titleGenerator);
        $this->itemRepository = $itemRepository;
    }

    public function getDetail($id)
    {
        /** @var Authority $author */
        $author = Authority::withCount('items')->find($id);

        if (!$author) {
            abort(404);
        }

        $author->incrementViewCount();
        return view('autor', [
            'author' => $author,
            'previewItems' => $this->itemRepository->getPreviewItems(10, $author),
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
        /** @var AbstractPaginator $paginator */
        $paginator = $data['paginator'];
        $paginator->getCollection()->each(function (Authority $authority) {
            $authority->previewItems = $this->itemRepository->getPreviewItems(10, $authority);
        });
        return $data;
    }
}
