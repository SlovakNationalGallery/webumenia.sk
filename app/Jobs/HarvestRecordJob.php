<?php

namespace App\Jobs;

use App\Harvest\SpiceHarvesterService;
use App\SpiceHarvesterRecord;

class HarvestRecordJob extends Job
{
    protected $record;

    public function __construct(SpiceHarvesterRecord $record) {
        $this->record = $record;
    }

    public function handle(SpiceHarvesterService $spiceHarvesterService) {
        $spiceHarvesterService->harvestRecord($this->record);
    }

    public function failed() {
        // @todo
    }
}