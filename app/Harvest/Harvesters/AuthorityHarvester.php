<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\AuthorityImporter;
use App\Harvest\Repositories\AuthorityRepository;
use App\Harvest\Progress;
use App\SpiceHarvesterRecord;

class AuthorityHarvester extends AbstractHarvester
{
    public function __construct(AuthorityRepository $repository, AuthorityImporter $importer) {
        parent::__construct($repository, $importer);
    }

    public function harvestRecord(SpiceHarvesterRecord $record, Progress $progress, array $row = null) {
        if ($row === null) {
            $row = $this->repository->getRow($record);
        }

        parent::harvestRecord($record, $progress, $row);
    }
}
