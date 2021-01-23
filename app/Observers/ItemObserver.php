<?php

namespace App\Observers;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;

class ItemObserver
{
    protected $itemRepository;

    protected $authorityRepository;

    public function __construct(
        ItemRepository $itemRepository,
        AuthorityRepository $authorityRepository
    ) {
        $this->itemRepository = $itemRepository;
        $this->authorityRepository = $authorityRepository;
    }

    public function saved(Item $item)
    {
        $this->itemRepository->indexAllLocales($item->fresh());
    }

    public function deleted(Item $item)
    {
        $this->itemRepository->deleteAllLocales($item);
    }

    public function deleting(Item $item)
    {
        $item->deleteImage();
        $item->collections()->detach();
    }

    public function belongsToManyAttached($relation, Item $item, $ids)
    {
        if ($item->exists) {
            $this->itemRepository->indexAllLocales($item->fresh());
            if ($relation === 'authorities') {
                Authority::findMany($ids)->each(function (Authority $authority) {
                    $this->authorityRepository->indexAllLocales($authority);
                });
            }
        }
    }

    public function belongsToManyDetached($relation, Item $item, $ids)
    {
        if ($item->exists) {
            $this->itemRepository->indexAllLocales($item->fresh());
            if ($relation === 'authorities') {
                Authority::findMany($ids)->each(function (Authority $authority) {
                    $this->authorityRepository->indexAllLocales($authority);
                });
            }
        }
    }
}
