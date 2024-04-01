<?php

namespace App\Repositories;

use App\Collection;
use App\Facades\Frontend;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class CollectionRepository
{
    public function getPaginated(): Paginator
    {
        $collections = Collection::query()
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate();

        $responses = Http::pool(
            fn(Pool $pool) => $collections
                ->filter(fn(Collection $collection) => $collection->filter_items_url)
                ->map(
                    fn(Collection $collection) => $pool
                        ->as($collection->id)
                        ->withHeaders(['X-Frontend' => Frontend::get()->value])
                        ->get($collection->filter_items_url)
                )
        );

        foreach ($responses as $response) {
            if ($response instanceof \Throwable) {
                throw $response;
            }
        }

        foreach ($collections as $collection) {
            if (isset($responses[$collection->id])) {
                $collection->filter_items_count = $responses[$collection->id]->json('total');
            }
        }

        return $collections;
    }
}
