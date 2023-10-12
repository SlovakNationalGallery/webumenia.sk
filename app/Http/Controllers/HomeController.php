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
use App\ShuffledItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $locale = App::currentLocale();

        $shuffledItems = Cache::rememberForever("home.shuffled-items.$locale", function () use (
            $locale
        ) {
            return ShuffledItem::query()
                ->translatedIn($locale)
                ->withTranslation()
                ->with(['media', 'item', 'item.authorities', 'item.translations'])
                ->published()
                ->get()
                ->map(fn($si) => $si->toShuffleOrchestratorItem());
        });

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

        [$featuredAuthor, $featuredAuthorItems] = Cache::remember(
            'home.featured-author',
            now()->addDays(7),
            function () {
                $author = Authority::query()
                    ->with(['translations'])
                    ->withCount('items')
                    ->where('has_image', true)
                    ->where('type', 'person')
                    ->whereHas(
                        'items',
                        function (Builder $query) {
                            $query->has('images');
                        },
                        '>=',
                        3
                    )
                    // ->inRandomOrder()
                    ->first();

                if (!$author) {
                    return [null, null];
                }

                $items = $author
                    ->items()
                    ->where('has_image', true)
                    // ->inRandomOrder()
                    ->limit(10)
                    ->get();

                return [$author, $items];
            }
        );

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
                'shuffledItems',
                'featuredPiece',
                'featuredArtwork',
                'featuredAuthor',
                'featuredAuthorItems',
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
            $galleriesCount = count(config('galleries'));
            $authoritiesCount = Authority::count();
            $itemsCount = Item::count();
            $highResItemsCount = Item::whereHas('images')->count();

            $itemRepository = app(ItemRepository::class);
            $freeItemsCount = $itemRepository->count((new ItemFilter())->setIsFree(true));

            return [
                [
                    'start_lang_string' => 'home.from_galleries_start',
                    'url' => route('frontend.info'),
                    'count' => $galleriesCount,
                    'end_lang_string' => 'home.from_galleries_end',
                    'items_count' => $itemsCount,
                ],
                [
                    'start_lang_string' => 'home.from_authors_start',
                    'url' => route('frontend.author.index'),
                    'count' => $authoritiesCount,
                    'end_lang_string' => 'home.from_authors_end',
                    'items_count' => $itemsCount,
                ],
                [
                    'start_lang_string' => 'home.in_high_res_start',
                    'url' => route('frontend.catalog.index', ['has_iip' => true]),
                    'count' => $highResItemsCount,
                    'end_lang_string' => 'home.in_high_res_end',
                    'items_count' => $itemsCount,
                ],
                [
                    'start_lang_string' => 'home.are_free_start',
                    'url' => route('frontend.catalog.index', ['is_free' => true]),
                    'count' => $freeItemsCount,
                    'end_lang_string' => 'home.are_free_end',
                    'items_count' => $itemsCount,
                ],
            ];
        });

        return (object) $choices[0];
    }
}
