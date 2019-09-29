<?php

namespace App\Http\Controllers;

use App\Elasticsearch\Repositories\TranslatableRepository;
use App\Filter\Contracts\SearchRequest;
use App\Filter\Contracts\TitleGenerator;
use Astrotomic\Translatable\Contracts\Translatable;
use Barryvdh\Form\CreatesForms;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

abstract class AbstractSearchRequestController extends Controller
{
    use CreatesForms;

    protected $perPage = 18;

    protected $repository;

    protected $titleGenerator;

    protected $searchRequestClass;

    protected $searchRequestFormClass;

    protected $indexView;

    public function __construct(
        TranslatableRepository $repository,
        TitleGenerator $titleGenerator
    ) {
        $this->repository = $repository;
        $this->titleGenerator = $titleGenerator;
    }

    public function getIndex()
    {
        return view($this->indexView, $this->getIndexData());
    }

    public function getSuggestions()
    {
        return response()->json($this->getSuggestionsData());
    }

    protected function getIndexData()
    {
        /** @var SearchRequest $searchRequest */
        $searchRequest = new $this->searchRequestClass;
        $currentPage = Paginator::resolveCurrentPage();

        // populate filter with input data
        $this->createSearchRequestForm($searchRequest)->handleRequest();

        // create form with filtered fields
        $searchRequestForm = $this->createSearchRequestForm($searchRequest);

        $searchRequest->setFrom(($currentPage - 1) * $this->perPage)
            ->setSize($this->perPage);

        $response = $this->repository->search($searchRequest);
        $collection = $response->getCollection();
        $paginator = new LengthAwarePaginator(
            $collection,
            $response->getTotal(),
            $this->perPage,
            $currentPage,
            ['path' => sprintf('/%s', request()->path())]
        );

        return [
            'paginator' => $paginator,
            'hasFilters' => (bool)$searchRequest->toQuery(),
            'searchRequest' => $searchRequest,
            'form' => $searchRequestForm->createView(),
            'title' => $this->titleGenerator->generate($searchRequest),
            'untranslated' => $collection->contains(function (Translatable $translatable) {
                return !$translatable->hasTranslation();
            }),
        ];
    }

    protected function getSuggestionsData()
    {
        try {
            $this->validate(request(), [
                'search' => 'required|string'
            ]);
            $search = request()->get('search');
            return $this->repository->getSuggestions(10, $search)->getCollection();
        } catch (ValidationException $e) {
            return collect();
        }
    }

    protected function createSearchRequestForm($searchRequest)
    {
        return $this->getFormFactory()
            ->createNamedBuilder(null, $this->searchRequestFormClass, $searchRequest, [
                'allow_extra_fields' => true,
            ])
            ->getForm();
    }
}
