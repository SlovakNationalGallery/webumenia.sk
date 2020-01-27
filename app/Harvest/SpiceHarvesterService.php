<?php

namespace App\Harvest;

use App\Harvest\Harvesters\AbstractHarvester;
use App\Harvest\Harvesters\AuthorityHarvester;
use App\Harvest\Harvesters\ItemHarvester;
use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;
use Psr\Log\LoggerInterface;

class SpiceHarvesterService
{
    /** @var AbstractHarvester[] */
    protected $harvesters;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(
        ItemHarvester $itemHarvester,
        AuthorityHarvester $authorityHarvester,
        LoggerInterface $logger
    ) {
        $this->harvesters = [
            'item' => $itemHarvester,
            'author' => $authorityHarvester
        ];

        $this->logger = $logger;
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @param \DateTime $from
     * @param \DateTime $to
     */
    public function harvest(SpiceHarvesterHarvest $harvest, \DateTime $from = null, \DateTime $to = null, $only_ids = []) {
        $this->harvesters[$harvest->type]->tryHarvest($harvest, $from, $to, $only_ids);
    }

    /**
     * @param SpiceHarvesterRecord $record
     */
    public function harvestSingle(SpiceHarvesterRecord $record) {
        $this->harvesters[$record->type]->tryHarvestSingle($record, new Result());
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     */
    public function harvestFailed(SpiceHarvesterHarvest $harvest) {
        $failed = $harvest->records()->failed()->get();
        foreach ($failed as $record) {
            $this->harvestSingle($record);
        }
    }
}