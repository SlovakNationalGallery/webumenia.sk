<?php

namespace App\Harvest;

use App\Harvest\Harvesters\AbstractHarvester;
use App\Harvest\Harvesters\AuthorityHarvester;
use App\Harvest\Harvesters\ItemHarvester;
use App\Harvest\Importers\Result;
use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;
use Psr\Log\LoggerInterface;

class SpiceHarvesterService
{
    /** @var AbstractHarvester[] */
    protected $harvesters;

    /** @var LoggerInterface */
    protected $logger;

    protected $statusMessages = [
        'Spracovaných bolo %d záznamov.',
        '%d nových záznamov',
        '%d bolo upravených',
        '%d bolo zmazaných',
        '%d bolo preskočených.',
        'Trvalo to %.0fs'
    ];

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
    public function harvest(SpiceHarvesterHarvest $harvest, \DateTime $from = null, \DateTime $to = null) {
        $timeStart = microtime(true);

        $this->harvesters[$harvest->type]->harvest($harvest, $result = new Result(), $from, $to);

        $harvest->status = SpiceHarvesterHarvest::STATUS_COMPLETED;
        $harvest->status_messages .= sprintf(
            implode(PHP_EOL, $this->statusMessages),
            $result->getTotal(),
            $result->getInserted(),
            $result->getUpdated(),
            $result->getDeleted(),
            $result->getSkipped(),
            microtime(true) - $timeStart
        );
        $harvest->completed = date('Y-m-d H:i:s');
        $harvest->save();
    }

    /**
     * @param SpiceHarvesterRecord $record
     */
    public function harvestSingle(SpiceHarvesterRecord $record) {
        $this->harvesters[$record->type]->harvestSingle($record, new Result());
    }
}