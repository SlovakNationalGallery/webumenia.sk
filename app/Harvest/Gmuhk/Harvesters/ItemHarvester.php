<?php

namespace App\Harvest\Gmuhk\Harvesters;

use App\Harvest\Harvesters\AbstractHarvester;
use App\Harvest\Gmuhk\Importers\ItemImporter;
use App\Harvest\Gmuhk\Repositories\ItemRepository;

class ItemHarvester extends AbstractHarvester
{
    public function __construct(ItemRepository $repository, ItemImporter $importer)
    {
        parent::__construct($repository, $importer);
    }
}