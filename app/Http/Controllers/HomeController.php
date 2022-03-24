<?php

namespace App\Http\Controllers;

use App\Article;
use App\Authority;
use App\Collection;
use App\Elasticsearch\Repositories\ItemRepository;
use App\FeaturedArtwork;
use App\FeaturedPiece;
use App\Filter\ItemFilter;
use App\Item;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // TODO caching
        $featuredPiece = FeaturedPiece::query()
            ->published()
            ->with('media')
            ->orderBy('updated_at', 'desc')
            ->first();

        $featuredArtwork = FeaturedArtwork::query()
            ->published()
            ->orderBy('updated_at', 'desc')
            ->first();

        $featuredAuthor = Authority::query()
            ->where('has_image', true)
            ->where('type', 'person')
            ->has('items', '>', 3)
            ->inRandomOrder()
            ->first();

        $articles = Article::query()
            ->with(['translations', 'category'])
            ->published()
            ->orderBy('published_date', 'desc')
            ->limit(5)
            ->get();

        $articlesTotalCount = Article::published()->count();
        $articlesRemainingCount = floor(($articlesTotalCount - 5) / 10) * 10; // Round down to nearest 10

        $collections = Collection::query()
            ->with(['translations', 'user'])
            ->withCount('items')
            ->published()
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $collectionsTotalCount = Collection::published()->count();
        $collectionsRemainingCount = floor(($collectionsTotalCount - 5) / 10) * 10; // Round down to nearest 10
        $countsBlurb = $this->getCountsBlurbData();

        return view('home.index')->with(
            compact([
                'featuredPiece',
                'featuredArtwork',
                'featuredAuthor',
                'articles',
                'articlesRemainingCount',
                'collections',
                'collectionsRemainingCount',
                'countsBlurb',
            ])
        );
    }

    private function getCountsBlurbData()
    {
        $choices = Cache::remember('home.counts', now()->addDays(3), function () {
            $galleriesCount = 15;
            $authoritiesCount = Authority::count();
            $itemsCount = Item::count();
            $highResItemsCount = Item::withExists('images')->count();

            $itemRepository = app(ItemRepository::class);
            $freeItemsCount = $itemRepository->count((new ItemFilter())->setIsFree(true));

            return [
                [
                    'start' => trans('intro.from_galleries_start'),
                    'url' => route('frontend.info'),
                    'count' => $galleriesCount,
                    'end' => trans('intro.from_galleries_end'),
                    'itemsCount' => $itemsCount,
                ],
                [
                    'start' => trans('intro.from_authors_start'),
                    'url' => route('frontend.author.index'),
                    'count' => $authoritiesCount,
                    'end' => trans('intro.from_authors_end'),
                    'itemsCount' => $itemsCount,
                ],
                [
                    'start' => trans('intro.in_high_res_start'),
                    'url' => route('frontend.catalog.index', ['has_iip' => true]),
                    'count' => $highResItemsCount,
                    'end' => trans('intro.in_high_res_end'),
                    'itemsCount' => $itemsCount,
                ],
                [
                    'start' => trans('intro.are_free_start'),
                    'url' => route('frontend.catalog.index', ['is_free' => true]),
                    'count' => $freeItemsCount,
                    'end' => trans('intro.are_free_end'),
                    'itemsCount' => $itemsCount,
                ],
            ];
        });

        return (object) $choices[array_rand($choices)];
    }
}
