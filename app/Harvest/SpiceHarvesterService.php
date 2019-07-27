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
        $timeStart = microtime(true);
        $result = new Result();

        try {
            $this->harvesters[$harvest->type]->harvest($harvest, $result, $from, $to, $only_ids);

            $harvest->status = SpiceHarvesterHarvest::STATUS_COMPLETED;
            $harvest->status_messages = '';
            $harvest->completed = date('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $harvest->status = SpiceHarvesterHarvest::STATUS_ERROR;
            $harvest->status_messages = $e->getMessage() . PHP_EOL;

            app('sentry')->captureException($e);
        }

        $harvest->status_messages .= sprintf(
            trans('harvest.status_message_completed'),
            $result->getTotal(),
            $result->getInserted(),
            $result->getUpdated(),
            $result->getDeleted(),
            $result->getSkipped(),
            microtime(true) - $timeStart
        );

        $harvest->save();
    }

    /**
     * @param SpiceHarvesterRecord $record
     */
    public function harvestSingle(SpiceHarvesterRecord $record) {
        $this->harvesters[$record->type]->harvestSingle($record, new Result());
    }
}