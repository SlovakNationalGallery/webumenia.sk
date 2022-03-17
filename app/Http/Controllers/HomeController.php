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
        $featuredPiece = Cache::rememberForever('home.featured-piece', function () {
            return FeaturedPiece::query()
                ->published()
                ->with('media')
                ->orderBy('updated_at', 'desc')
                ->first();
        });

        $featuredArtwork = Cache::rememberForever('home.featured-artwork', function () {
            return FeaturedArtwork::query()
                ->with(['item.authorities', 'item.translations'])
                ->published()
                ->orderBy('updated_at', 'desc')
                ->first();
        });

        $featuredAuthor = Cache::remember('home.featured-author', now()->addDays(7), function () {
            return Authority::query()
                ->with(['translations', 'items'])
                ->where('has_image', true)
                ->where('type', 'person')
                ->has('items', '>', 3)
                ->inRandomOrder()
                ->first();
        });

        [$articles, $articlesRemainingCount] = Cache::rememberForever('home.articles', function () {
            $articles = Article::query()
                ->with(['translations', 'category'])
                ->published()
                ->orderBy('published_date', 'desc')
                ->limit(5)
                ->get();

            $totalCount = Article::published()->count();
            $remainingCount = floor(($totalCount - 5) / 10) * 10; // Round down to nearest 10

            return [$articles, $remainingCount];
        });

        [$collections, $collectionsRemainingCount] = Cache::rememberForever(
            'home.collections',
            function () {
                $collections = Collection::query()
                    ->with(['translations', 'user'])
                    ->withCount('items')
                    ->published()
                    ->orderBy('published_at', 'desc')
                    ->take(5)
                    ->get();

                $totalCount = Collection::published()->count();
                $remainingCount = floor(($totalCount - 5) / 10) * 10; // Round down to nearest 10

                return [$collections, $remainingCount];
            }
        );

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
        [$itemsCount, $choices] = Cache::remember('home.counts', now()->addDays(3), function () {
            $galleriesCount = 15;
            $authoritiesCount = Authority::count();
            $itemsCount = Item::count();
            $highResItemsCount = Item::withExists('images')->count();

            $itemRepository = app(ItemRepository::class);
            $freeItemsCount = $itemRepository->count((new ItemFilter())->setIsFree(true));

            return [
                $itemsCount,
                [
                    [
                        trans('intro.from_galleries_start'),
                        route('frontend.info'),
                        $galleriesCount,
                        trans('intro.from_galleries_end'),
                    ],
                    [
                        trans('intro.from_authors_start'),
                        route('frontend.author.index'),
                        $authoritiesCount,
                        trans('intro.from_authors_end'),
                    ],
                    [
                        trans('intro.in_high_res_start'),
                        route('frontend.catalog.index', ['has_iip' => true]),
                        $highResItemsCount,
                        trans('intro.in_high_res_end'),
                    ],
                    [
                        trans('intro.are_free_start'),
                        route('frontend.catalog.index', ['is_free' => true]),
                        $freeItemsCount,
                        trans('intro.are_free_end'),
                    ],
                ],
            ];
        });

        return (object) [
            'parts' => $choices[array_rand($choices)],
            'itemsCount' => $itemsCount,
        ];
    }
}
