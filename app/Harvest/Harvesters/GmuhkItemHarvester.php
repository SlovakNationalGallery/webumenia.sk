<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\MuseionItemImporter;
use App\Harvest\Mappers\GmuhkItemMapper;
use App\Harvest\Repositories\MuseionItemRepository;
use App\Matchers\AuthorityMatcher;

class GmuhkItemHarvester extends AbstractHarvester
{
    public function __construct(MuseionItemRepository $repository, GmuhkItemMapper $mapper, AuthorityMatcher $authorityMatcher)
    {
        $importer = new MuseionItemImporter($mapper, $authorityMatcher);
        parent::__construct($repository, $importer);
    }
}
