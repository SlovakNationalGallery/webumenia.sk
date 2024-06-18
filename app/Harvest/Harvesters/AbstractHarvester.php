<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\AbstractImporter;
use App\Harvest\Repositories\AbstractRepository;
use App\Harvest\Progress;
use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

abstract class AbstractHarvester
{
    public function __construct(protected AbstractRepository $repository, protected AbstractImporter $importer)
    {
    }

    public function harvestFailed(SpiceHarvesterHarvest $harvest) {
        $harvest->process(function (Progress $progress) use ($harvest) {
            $failed = $harvest->records()->failed()->get();
            $progress->setTotal(count($failed));

            foreach ($failed as $record) {
                $record->process(function () use ($record, $harvest, $progress) {
                    $this->harvestRecord($record, $progress);
                }, $progress);
                $harvest->updateStatusMessages($progress);
            }
        });
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @param \DateTime|null $from
     * @param \DateTime|null $to
     * @param array $only_ids
     */
    public function harvest(SpiceHarvesterHarvest $harvest, \DateTime $from = null, \DateTime $to = null) {
        $harvest->process(function (Progress $progress) use ($harvest, $from, $to) {
            $result = $this->repository->getAll($harvest, $from, $to);
            $total = $result->total;
            $rows = $result->data;

            $progress->setTotal($total);

            foreach ($rows as $row) {
                $record = SpiceHarvesterRecord::firstOrNew([
                    'identifier' => $this->importer->getIdentifier($row),
                    'type' => $this->importer->getModelClass(),
                    'harvest_id' => $harvest->id,
                ]);

                $record->item_id = $this->importer->getModelId($row);
                if ($record->item_id === null) {
                    $progress->incrementSkipped();
                    continue;
                }

                $this->harvestRecord($record, $progress, $row);
                $harvest->updateStatusMessages($progress);
            }
        });
    }

    /**
     * @param SpiceHarvesterRecord $record
     * @param Progress $progress
     * @param array|null $row
     */
    public function harvestRecord(SpiceHarvesterRecord $record, Progress $progress, array $row = null) {
        $record->process(function () use ($record, $progress, $row) {
            if ($row === null) {
                $row = $this->repository->getRow($record);
            }

            $this->processRecord($record, $progress, $row);
        }, $progress);
    }

    protected function processRecord(SpiceHarvesterRecord $record, Progress $progress, array $row) {
        if ($record->trashed() || $this->isExcluded($row)) {
            $progress->incrementSkipped();
            return null;
        }

        if ($this->isForDeletion($row)) {
            $this->deleteRecord($record);
            $progress->incrementDeleted();
            return null;
        }

        $this->importer->import($row, $progress);

        if (Arr::get($row, 'datestamp.0')) {
            $record->datestamp = Carbon::parse(Arr::get($row, 'datestamp.0'));
        }
    }

    /**
     * @param SpiceHarvesterRecord $record
     */
    protected function deleteRecord(SpiceHarvesterRecord $record) {
        // @todo polymorphic item
        $model = $record->item;
        if ($model) {
            $model->delete();
        }
        $record->delete();
        // refresh relations after soft-deleting record
        $record->refresh();
    }

    /**
     * @param array $row
     * @return bool
     */
    protected function isForDeletion(array $row) {
        return Arr::get($row, 'status.0', null) === 'deleted';
    }

    /**
     * @param array $row
     * @return bool
     */
    protected function isExcluded(array $row) {
        return false;
    }
}
