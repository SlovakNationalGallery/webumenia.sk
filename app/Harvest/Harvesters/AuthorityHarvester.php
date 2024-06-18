<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\AuthorityImporter;
use App\Harvest\Repositories\AuthorityRepository;

class AuthorityHarvester extends AbstractHarvester
{
    public function __construct(AuthorityRepository $repository, AuthorityImporter $importer)
    {
        parent::__construct($repository, $importer);
    }
}
