<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\AbstractImporter;
use App\Harvest\Repositories\AbstractRepository;
use App\Harvest\Progress;
use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;
use Carbon\Carbon;

abstract class AbstractHarvester
{
    /** @var AbstractImporter */
    protected $importer;

    public function __construct(AbstractRepository $repository, AbstractImporter $importer) {
        $this->repository = $repository;
        $this->importer = $importer;
    }

    public function harvestFailed(SpiceHarvesterHarvest $harvest) {
        $harvest->process(function (Progress $progress) use ($harvest) {
            $failed = $harvest->records()->failed()->get();
            $progress->setTotal(count($failed));

            foreach ($failed as $record) {
                $this->harvestRecord($record, $progress);
                $harvest->advance($progress);
            }
        });
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @param \DateTime|null $from
     * @param \DateTime|null $to
     * @param array $only_ids
     */
    public function harvest(SpiceHarvesterHarvest $harvest, \DateTime $from = null, \DateTime $to = null, array $only_ids = []) {
        $harvest->process(function (Progress $progress) use ($harvest, $from, $to, $only_ids) {
            if ($only_ids) {
                $rows = $this->repository->getRowsById($harvest, $only_ids);
                $total = count($only_ids);
            } else {
                $rows = $this->repository->getRows($harvest, $from, $to, $total);
            }

            $progress->setTotal($total);

            foreach ($rows as $row) {
                $harvest->advance($progress);

                $modelId = $this->importer->getModelId($row);
                if ($modelId === null) {
                    $progress->incrementSkipped();
                    continue;
                }

                $record = SpiceHarvesterRecord::firstOrNew([
                    'identifier' => $modelId,
                    'type' => $harvest->type,
                ]);
                $record->harvest()->associate($harvest);
                $record->item_id = $modelId;

                $record->process(function (SpiceHarvesterRecord $record) use ($progress, $row) {
                    $this->harvestRecord($record, $progress, $row);
                });
            }

            $harvest->status_messages = trans('harvest.status_messages.completed', [
                'processed' => $progress->getProcessed(),
                'created' => $progress->getInserted(),
                'updated' => $progress->getUpdated(),
                'deleted' => $progress->getDeleted(),
                'skipped' => $progress->getSkipped(),
                'time' => Carbon::now()->diffInSeconds($progress->getCreatedAt()),
            ]);
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
            $record->datestamp = $row['datestamp'][0];
        });
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
    }

    /**
     * @param array $row
     * @return bool
     */
    protected function isForDeletion(array $row) {
        return isset($row['status'][0]) && $row['status'][0] == 'deleted';
    }

    /**
     * @param array $row
     * @return bool
     */
    protected function isExcluded(array $row) {
        return false;
    }
}
