<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\MuseionItemImporter;
use App\Harvest\Mappers\MudbItemMapper;
use App\Harvest\Repositories\MuseionItemRepository;
use App\Matchers\AuthorityMatcher;

class MudbItemHarvester extends AbstractHarvester
{
    public function __construct(MuseionItemRepository $repository, MudbItemMapper $mapper, AuthorityMatcher $authorityMatcher)
    {
        $importer = new MuseionItemImporter($mapper, $authorityMatcher);
        parent::__construct($repository, $importer);
    }
}
