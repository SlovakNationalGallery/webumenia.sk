<?php

namespace App\Harvest;

use App\Harvest\Harvesters\AbstractHarvester;
use App\Harvest\Harvesters\AuthorityHarvester;
use App\Harvest\Harvesters\ItemHarvester;
use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;

class SpiceHarvesterService
{
    /** @var AbstractHarvester[] */
    protected $harvesters;

    public function __construct(
        ItemHarvester $itemHarvester,
        AuthorityHarvester $authorityHarvester
    ) {
        $this->harvesters = [
            'item' => $itemHarvester,
            'author' => $authorityHarvester
        ];
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @param \DateTime $from
     * @param \DateTime $to
     */
    public function harvest(SpiceHarvesterHarvest $harvest, \DateTime $from = null, \DateTime $to = null, $only_ids = []) {
        $this->harvesters[$harvest->type]->harvest($harvest, $from, $to, $only_ids);
    }

    /**
     * @param SpiceHarvesterRecord $record
     */
    public function harvestRecord(SpiceHarvesterRecord $record) {
        $this->harvesters[$record->type]->harvestRecord($record, new Progress());
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     */
    public function harvestFailed(SpiceHarvesterHarvest $harvest) {
        $this->harvesters[$harvest->type]->harvestFailed($harvest);
    }
}