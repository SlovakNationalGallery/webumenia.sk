<?php

namespace App\Harvest;

use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;

class SpiceHarvesterService
{
    /**
     * @param SpiceHarvesterHarvest $harvest
     * @param \DateTime $from
     * @param \DateTime $to
     */
    public function harvest(SpiceHarvesterHarvest $harvest, \DateTime $from = null, \DateTime $to = null) {
        app()->make($harvest->type)->harvest($harvest, $from, $to);
    }

    /**
     * @param SpiceHarvesterRecord $record
     */
    public function harvestRecord(SpiceHarvesterRecord $record) {
        app()->make($record->harvest->type)->harvestRecord($record, new Progress());
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     */
    public function harvestFailed(SpiceHarvesterHarvest $harvest) {
        app()->make($harvest->type)->harvestFailed($harvest);
    }
}