<?php

namespace App\Observers;

use App\Authority;
use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Item;

class AuthorityObserver
{
    protected $authorityRepository;

    protected $itemRepository;

    public function __construct(
        AuthorityRepository $authorityRepository,
        ItemRepository $itemRepository
    ) {
        $this->authorityRepository = $authorityRepository;
        $this->itemRepository = $itemRepository;
    }

    public function saved(Authority $authority)
    {
        $this->authorityRepository->indexAllLocales($authority->refresh());
        $authority->items->each(function (Item $item) {
            $this->itemRepository->indexAllLocales($item);
        });
    }

    public function deleted(Authority $authority)
    {
        $this->authorityRepository->deleteAllLocales($authority);
        $authority->items->each(function (Item $item) {
            $this->itemRepository->indexAllLocales($item);
        });
    }

    public function deleting(Authority $authority)
    {
        $authority->removeImage();
        $authority->nationalities()->detach();
        $authority->relationships()->detach();
        $authority->items()->detach();
        $authority->names()->delete();
        $authority->events()->delete();
    }
    public function belongsToManyAttached($relation, Authority $authority, $ids)
    {
        if ($authority->exists) {
            $this->authorityRepository->indexAllLocales($authority);
            if ($relation === 'items') {
                Item::findMany($ids)->each(function (Item $item) {
                    $this->itemRepository->indexAllLocales($item);
                });
            }
        }
    }

    public function belongsToManyDetached($relation, Authority $authority, $ids)
    {
        if ($authority->exists) {
            $this->authorityRepository->indexAllLocales($authority);
            if ($relation === 'items') {
                Item::findMany($ids)->each(function (Item $item) {
                    $this->itemRepository->indexAllLocales($item);
                });
            }
        }
    }
}
