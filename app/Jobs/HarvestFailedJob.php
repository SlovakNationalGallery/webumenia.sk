<?php

namespace App\Jobs;

use App\Harvest\SpiceHarvesterService;
use App\SpiceHarvesterHarvest;
use Illuminate\Contracts\Queue\ShouldQueue;

class HarvestFailedJob extends Job implements ShouldQueue
{
    protected $harvest;

    public function __construct(SpiceHarvesterHarvest $harvest) {
        $this->harvest = $harvest;
    }

    public function handle(SpiceHarvesterService $spiceHarvesterService) {
        $spiceHarvesterService->harvestFailed($this->harvest);
    }
}