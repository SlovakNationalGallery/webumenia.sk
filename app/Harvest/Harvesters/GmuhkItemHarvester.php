<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Harvesters\AbstractHarvester;
use App\Harvest\Importers\GmuhkItemImporter;
use App\Harvest\Repositories\GmuhkItemRepository;

class GmuhkItemHarvester extends AbstractHarvester
{
    public function __construct(GmuhkItemRepository $repository, GmuhkItemImporter $importer)
    {
        parent::__construct($repository, $importer);
    }
}