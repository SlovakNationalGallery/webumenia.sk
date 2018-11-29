<?php

namespace App\Jobs;

use App\Harvest\SpiceHarvesterService;
use App\SpiceHarvesterHarvest;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;

class HarvestJob extends Job implements ShouldQueue
{
    /** @var SpiceHarvesterHarvest */
    protected $harvest;

    /** @var \DateTime */
    protected $from;

    /** @var \DateTime */
    protected $to;

    /** @var bool */
    protected $all;

    /** @var array */
    protected $only_ids;

    public function __construct(SpiceHarvesterHarvest $harvest, \DateTime $from = null, \DateTime $to = null, $all = false, $only_ids = []) {
        $this->harvest = $harvest;
        if ($all) {
            $from = null;
            $to = null;
        }
        $this->all = $all;
        $this->from = $from;
        $this->to = $to;
        $this->only_ids = $only_ids;

    }

    public function handle(SpiceHarvesterService $spiceHarvesterService) {
        if (!$this->all && !$this->from && $this->harvest->completed) {
            $this->from = (new Carbon($this->harvest->completed))->subDay();
        }

        $spiceHarvesterService->harvest($this->harvest, $this->from, $this->to, $this->only_ids);
    }

    public function failed() {
        // @todo
    }
}