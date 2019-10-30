<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\AbstractImporter;
use App\Harvest\Repositories\AbstractRepository;
use App\Harvest\Result;
use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractHarvester
{
    /** @var AbstractImporter */
    protected $importer;

    public function __construct(AbstractRepository $repository, AbstractImporter $importer) {
        $this->repository = $repository;
        $this->importer = $importer;
    }

    public function tryHarvest(SpiceHarvesterHarvest $harvest, \DateTime $from = null, \DateTime $to = null, $only_ids = []) {
        $models = [];

        try {
            $harvest->status = SpiceHarvesterHarvest::STATUS_IN_PROGRESS;
            $harvest->status_messages = trans('harvest.status_messages.started');
            $harvest->save();

            $result = new Result();
            $models = $this->harvest($harvest, $result, $from, $to, $only_ids);

            $harvest->status = SpiceHarvesterHarvest::STATUS_COMPLETED;
            $harvest->completed = date('Y-m-d H:i:s');
            $harvest->status_messages = trans('harvest.status_messages.completed', [
                'processed' => $result->getTotal(),
                'created' => $result->getInserted(),
                'updated' => $result->getUpdated(),
                'deleted' => $result->getDeleted(),
                'skipped' => $result->getSkipped(),
                'time' => Carbon::now()->diffInSeconds($result->getCreatedAt()),
            ]);
        } catch (\Exception $e) {
            $harvest->status = SpiceHarvesterHarvest::STATUS_ERROR;
            $harvest->status_messages = trans('harvest.status_messages.error', [
                'error' => $e->getMessage(),
            ]);
            app('sentry')->captureException($e);
        } finally {
            $harvest->save();
        }

        return $models;
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @param Result $result
     * @param \DateTime $from
     * @param \DateTime $to
     * @return Model[]
     */
    protected function harvest(SpiceHarvesterHarvest $harvest, Result $result, \DateTime $from = null, \DateTime $to = null, $only_ids = []) {
        $models = [];

        if (!empty($only_ids)) {
            $rows = $this->repository->getRowsById($harvest, $only_ids);
            $total = count($only_ids);
        } else {
            $rows = $this->repository->getRows($harvest, $from, $to, $total);
        }

        $i = 0;
        foreach ($rows as $row) {
            $harvest->status_messages = trans('harvest.status_messages.progress', [
                'current' => ++$i,
                'total' => $total,
            ]);
            $harvest->save();

            $modelId = $this->importer->getModelId($row);
            if ($modelId === null) {
                $result->incrementSkipped();
                continue;
            }

            $record = SpiceHarvesterRecord::firstOrNew([
                'identifier' => $modelId,
                'type' => $harvest->type,
            ]);
            $record->harvest()->associate($harvest);
            $record->item_id = $modelId;

            if ($model = $this->tryHarvestSingle($record, $result, $row)) {
                $models[] = $model;
            }
        }

        return $models;
    }

    public function tryHarvestSingle(SpiceHarvesterRecord $record, Result $result, array $row = null) {
        $model = null;

        try {
            $model = $this->harvestSingle($record, $result, $row);

            $record->failed_at = null;
            $record->error_message = null;
        } catch (\Exception $e) {
            $record->failed_at = Carbon::now();
            $record->error_message = $e->getMessage();
            app('sentry')->captureException($e);
        } finally {
            if (!$record->deleted_at) {
                $record->save();
            }
        }

        return $model;
    }

    /**
     * @param SpiceHarvesterRecord $record
     * @param Result $result
     * @param array $row
     * @return Model|null
     */
    protected function harvestSingle(SpiceHarvesterRecord $record, Result $result, array $row = null) {
        if ($row === null) {
            $row = $this->repository->getRow($record);
        }

        if ($record->trashed() || $this->isExcluded($row)) {
            $result->incrementSkipped();
            return null;
        }

        if ($this->isForDeletion($row)) {
            $this->deleteRecord($record);
            $result->incrementDeleted();
            return null;
        }

        $model = $this->importer->import($row, $result);
        $record->datestamp = $row['datestamp'][0];

        return $model;
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