<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\AbstractImporter;
use App\Harvest\Importers\Result;
use App\Harvest\Repositories\AbstractRepository;
use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;

abstract class AbstractHarvester
{
    /** @var AbstractImporter */
    protected $importer;

    public function __construct(AbstractRepository $repository, AbstractImporter $importer) {
        $this->repository = $repository;
        $this->importer = $importer;
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @param Result $result
     * @param \DateTime $from
     * @param \DateTime $to
     * @return Model[]
     */
    public function harvest(SpiceHarvesterHarvest $harvest, Result $result, \DateTime $from = null, \DateTime $to = null) {
        $model = [];

        $rows = $this->repository->getRows($harvest, $from, $to);
        foreach ($rows as $row) {
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

            if ($model = $this->harvestSingle($record, $result, $row)) {
                $models[] = $model;
            }
        }

        return $model;
    }

    /**
     * @param SpiceHarvesterRecord $record
     * @param Result $result
     * @param array $row
     * @return Model
     */
    public function harvestSingle(SpiceHarvesterRecord $record, Result $result, array $row = null) {
        if ($row === null) {
            $row = $this->repository->getRow($record);
        }

        if ($record->trashed() || $this->isExcluded($row)) {
            $result->incrementSkipped();
            return;
        }

        if ($this->isForDeletion($row)) {
            $this->deleteRecord($record);
            $result->incrementDeleted();
            return;
        }

        $model = $this->importer->import($row, $result);

        $record->datestamp = $row['datestamp'][0];
        $record->save();

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