<?php

namespace App\Http\Controllers;

use App\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Response;

class KolekciaController extends Controller
{
    public function getIndex(Request $request)
    {
        $collections = Collection::query()->published();

        // Filtering
        if ($request->has('category')) {
            $collections->whereTranslation('type', $request->input('category'));
        }
        if ($request->has('author')) {
            $collections->whereRelation('user', 'name', $request->input('author'));
        }

        $filterOptions = $this->buildFilterOptions($collections, $request);

        // Sorting
        $sortBy = $request->input('sort_by', 'published_at');

        if ($sortBy === 'published_at') {
            $collections->orderBy('published_at', 'desc');
        }
        if ($sortBy === 'updated_at') {
            $collections->orderBy('updated_at', 'desc');
        }
        if ($sortBy === 'name') {
            $collections->orderByTranslation('name', 'asc');
        }

        $sortingOptions = collect([
            [
                'value' => 'published_at',
                'text' => trans('kolekcie.filter.sorting.published_at'),
            ],
            ['value' => 'updated_at', 'text' => trans('kolekcie.filter.sorting.updated_at')],
            ['value' => 'name', 'text' => trans('kolekcie.filter.sorting.name')],
        ]);

        return view('frontend.collection.index', [
            'collections' => $collections
                ->with('user', 'items', 'items.translations')
                ->withTranslation()
                ->paginate(18),
            'filterOptions' => $filterOptions,
            'sortingOptions' => $sortingOptions,
            'sortBy' => $sortBy,
        ]);
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

        return view('kolekcia', ['collection' => $collection]);
    }

    public function getSuggestions()
    {
        $q = FacadesRequest::has('search')
            ? str_to_alphanumeric(FacadesRequest::input('search'))
            : 'null';

        $result = Collection::published()
            ->whereTranslationLike('name', '%' . $q . '%')
            ->limit(5)
            ->get();

        $data = [];
        $data['results'] = [];
        $data['count'] = 0;

        foreach ($result as $key => $hit) {
            $data['count']++;
            $params = [
                'name' => $hit->name,
                'author' => $hit->user->name,
                'items' => $hit->items->count(),
                'url' => $hit->getUrl(),
                'image' => $hit->getResizedImage(70),
            ];
            $data['results'][] = array_merge($params);
        }

        return Response::json($data);
    }

    private function buildFilterOptions(EloquentBuilder $collectionsQuery, Request $request)
    {
        return [
            'type' => $collectionsQuery
                ->clone()
                ->listsTranslations('type')
                ->groupBy('type')
                ->select('type as value')
                ->selectRaw('count(type) as count')
                ->orderByDesc('count')
                ->orderBy('value')
                ->get()
                ->map(
                    fn($row) => [
                        'value' => $row->value,
                        'text' => "{$row->value} ($row->count)",
                        'selected' => $request->input('category') === $row->value,
                    ]
                ),

            'author' => $collectionsQuery
                ->clone()
                ->leftJoin('users as u', 'u.id', '=', 'collections.user_id')
                ->select('u.name as value')
                ->selectRaw('count(u.name) as count')
                ->groupBy('u.id')
                ->orderBy('count', 'desc')
                ->orderBy('value', 'asc')
                ->get()
                ->map(
                    fn($row) => [
                        'value' => $row->value,
                        'text' => "{$row->value} ({$row->count})",
                        'selected' => $request->input('author') === $row->value,
                    ]
                ),
        ];
    }
}
