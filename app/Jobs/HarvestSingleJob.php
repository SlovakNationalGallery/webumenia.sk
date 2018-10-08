<?php

namespace App\Jobs;

use App\Harvest\SpiceHarvesterService;
use App\SpiceHarvesterRecord;

class HarvestSingleJob extends Job
{
    protected $record;

    public function __construct(SpiceHarvesterRecord $record) {
        $this->record = $record;
    }

    public function handle(SpiceHarvesterService $spiceHarvesterService) {
        $spiceHarvesterService->harvestSingle($this->record);
    }

    public function failed() {
        // @todo
    }
}