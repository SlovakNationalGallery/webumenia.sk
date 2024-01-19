<?php

namespace App\Observers;

use App\Elasticsearch\Repositories\ItemRepository;
use App\ItemFrontend;

class ItemFrontendObserver
{
    public function __construct(
        protected ItemRepository $itemRepository
    ) {
    }

    public function saved(ItemFrontend $itemFrontend)
    {
        $this->itemRepository->indexAllLocales($itemFrontend->item->fresh());
    }

    public function deleted(ItemFrontend $itemFrontend)
    {
        $this->itemRepository->indexAllLocales($itemFrontend->item->fresh());
    }
}