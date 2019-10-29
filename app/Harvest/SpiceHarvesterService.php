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
        $timeStart = time();
        $result = new Result();

        $this->harvesters[$harvest->type]->harvest($harvest, $result, $from, $to, $only_ids);

        $harvest->status = SpiceHarvesterHarvest::STATUS_COMPLETED;
        $harvest->completed = date('Y-m-d H:i:s');
        $harvest->status_messages = trans('harvest.status_messages.completed', [
            'processed' => $result->getTotal(),
            'created' => $result->getInserted(),
            'updated' => $result->getUpdated(),
            'deleted' => $result->getDeleted(),
            'skipped' => $result->getSkipped(),
            'time' => time() - $timeStart,
        ]);
        $harvest->status_messages .= PHP_EOL . implode(PHP_EOL, $result->getErrorMessages());
        $harvest->save();
    }

    /**
     * @param SpiceHarvesterRecord $record
     */
    public function harvestSingle(SpiceHarvesterRecord $record) {
        $this->harvesters[$record->type]->harvestSingle($record, new Result());
    }
}