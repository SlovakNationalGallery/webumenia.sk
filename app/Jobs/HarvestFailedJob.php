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

        $harvest->status = SpiceHarvesterHarvest::STATUS_QUEUED;
        $harvest->status_messages = trans('harvest.status_messages.waiting');
        $harvest->save();
    }

    public function handle(SpiceHarvesterService $spiceHarvesterService) {
        $spiceHarvesterService->harvestFailed($this->harvest);
    }
}