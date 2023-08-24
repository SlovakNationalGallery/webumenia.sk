<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RedirectLegacyCatalogRequest
{
    private static $legacySingleValueFilters = ['years_range', 'color'];

    private static $legacyArrayFilters = [
        'author',
        'work_type',
        'object_type',
        'tag',
        'topic',
        'technique',
        'medium',
        'gallery',
        'credit',
        'related_work',
        'contributor',
    ];

    private static $legacyBooleanFilters = [
        'has_image',
        'has_iip',
        'is_free',
        'has_text',
        'is_for_reproduction',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getQueryString() === null || !$this->isLegacyCatalogRequest($request)) {
            return $next($request);
        }

        $query = [];
        $legacyFilters = array_merge(
            self::$legacySingleValueFilters,
            self::$legacyArrayFilters,
            self::$legacyBooleanFilters
        );

        foreach ($request->only($legacyFilters) as $filter => $value) {
            if ($filter === 'years_range') {
                [$from, $to] = explode(',', $value);
                $query['filter']['date_latest'] = ['gte' => $from];
                $query['filter']['date_earliest'] = ['lte' => $to];
                continue;
            }

            if (in_array($filter, self::$legacyBooleanFilters)) {
                $query['filter'][$filter] = $request->boolean($filter) ? 'true' : 'false';
                continue;
            }

            $query['filter'][$filter] = in_array($filter, self::$legacyArrayFilters)
                ? Arr::wrap($value)
                : $value;
        }

        $request->whenFilled('search', function ($search) use (&$query) {
            $query['q'] = $search;
        });

        $request->whenFilled('sort_by', function ($sort) use (&$query) {
            if ($sort === 'created_at') {
                $query['sort']['created_at'] = 'desc';
                return;
            }
            if ($sort === 'title') {
                $query['sort']['title'] = 'asc';
                return;
            }
            if ($sort === 'author') {
                $query['sort']['author'] = 'asc';
                return;
            }
            if ($sort === 'newest') {
                $query['sort']['date_earliest'] = 'desc';
                return;
            }
            if ($sort === 'oldest') {
                $query['sort']['date_earliest'] = 'asc';
                return;
            }
            if ($sort === 'view_count') {
                $query['sort']['view_count'] = 'desc';
                return;
            }
            if ($sort === 'random') {
                $query['sort']['random'] = 'asc';
                return;
            }
        });

        $queryString = Str::of(http_build_query($query));
        return redirect($request->url() . '?' . $queryString);
    }

    private function isLegacyCatalogRequest(Request $request): bool
    {
        if ($request->anyFilled(['filter', 'sort', 'q'])) {
            return false;
        }

        return true;
    }
}
