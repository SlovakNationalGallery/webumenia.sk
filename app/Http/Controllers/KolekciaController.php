<?php

namespace App\Http\Controllers;

use App\Authority;
use Illuminate\Support\Facades\Input;
use App\Collection;
use App\Filter\CollectionSearchRequest;
use App\Filter\Contracts\Filter;
use App\Filter\Forms\Types\CollectionSearchRequestType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Form\FormFactoryInterface;

class KolekciaController extends Controller
{

    public function getIndex()
    {

        $per_page = 18;
        if (Input::has('sort_by') && array_key_exists(Input::get('sort_by'), Collection::$sortable)) {
            $sort_by = Input::get('sort_by');
        } else {
            $sort_by = 'created_at';
        }

        $searchRequest = new CollectionSearchRequest();
        $this->createSearchRequestForm($searchRequest)->handleRequest();
        $searchRequestForm = $this->createSearchRequestForm($searchRequest);
        $currentPage = Paginator::resolveCurrentPage();

        $collections = CollectionSearchRequestType::prepareCollections($searchRequest);

        if ($sort_by == 'name') {
            $collections = $collections->orderBy('name', 'asc');
        } else {
            $collections = $collections->orderBy($sort_by, 'desc');
        }

        $total = $collections->count();

        $paginator = new LengthAwarePaginator(
            $collections,
            $total,
            $per_page,
            $currentPage,
            ['path' => sprintf('/%s', request()->path())]
        );
        // populate filter with input data
        $this->createSearchRequestForm($searchRequest)->handleRequest();

        return view('frontend.collection.index', [
            'collections' => $collections->paginate($per_page),
            'sort_by' => $sort_by,
            'form' => $searchRequestForm->createView(),

            'paginator' => $paginator,
            'total' => $total,
            'searchRequest' => $searchRequest,
        ]);
    }

    protected function createSearchRequestForm($searchRequest)
    {
        return $this->getFormFactory()
            ->createNamedBuilder(null, CollectionSearchRequestType::class, $searchRequest, [
                'allow_extra_fields' => true,
            ])
            ->getForm();
    }

    protected function getFormFactory()
    {
        return app(FormFactoryInterface::class);
    }

    public function getDetail($id)
    {
        // dd($id);
        $collection = Collection::find($id);
        if (empty($collection)) {
            abort(404);
        }
        $collection->view_count += 1;
        $collection->save();

        return view('kolekcia', array('collection' => $collection));
    }

    public function getSuggestions()
    {
        $q = (Input::has('search')) ? str_to_alphanumeric(Input::get('search')) : 'null';

        $result = Collection::published()->whereTranslationLike('name', '%' . $q . '%')->limit(5)->get();

        $data = array();
        $data['results'] = array();
        $data['count'] = 0;

        foreach ($result as $key => $hit) {
            $data['count']++;
            $params = array(
                'name' => $hit->name,
                'author' => $hit->user->name,
                'items' => $hit->items->count(),
                'url' => $hit->getUrl(),
                'image' => $hit->getResizedImage(70),
            );
            $data['results'][] = array_merge($params);
        }

        return Response::json($data);
    }
}
