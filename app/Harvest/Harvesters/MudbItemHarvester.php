<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\MuseionItemImporter;
use App\Harvest\Repositories\MuseionItemRepository;

class MudbItemHarvester extends AbstractHarvester
{
    public function __construct(MuseionItemRepository $repository, MuseionItemImporter $importer)
    {
        parent::__construct($repository, $importer);
    }
}
