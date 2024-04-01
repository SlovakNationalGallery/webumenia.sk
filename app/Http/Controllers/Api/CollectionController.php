<?php

namespace App\Http\Controllers\Api;

use App\Collection;
use App\Http\Controllers\Controller;
use App\Http\Resources\CollectionResource;
use App\Repositories\CollectionRepository;

class CollectionController extends Controller
{
    public function __construct(protected CollectionRepository $repository)
    {
    }

    public function index()
    {
        $paginator = $this->repository->getPaginated();
        $paginator->getCollection()->each->append('filter_items_count');
        return CollectionResource::collection($paginator);
    }

    public function show(Collection $collection)
    {
        $collection->append('filter_items_url');
        return new CollectionResource($collection);
    }
}
