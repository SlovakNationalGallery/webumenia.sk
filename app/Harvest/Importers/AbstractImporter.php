<?php

namespace App\Harvest\Importers;

use App\Harvest\Mappers\AbstractMapper;
use App\Harvest\Result;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class AbstractImporter
{
    /** @var AbstractMapper */
    protected $mapper;

    /** @var AbstractMapper[] */
    protected $mappers = [];

    /** @var AbstractMapper[] */
    protected $pivotMappers = [];

    /** @var string */
    protected $modelClass;

    /** @var array */
    protected $conditions = [];

    public function __construct(AbstractMapper $mapper) {
        $this->mapper = $mapper;
    }

    /**
     * @param array $row
     * @return mixed
     */
    abstract function getModelId(array $row);

    /**
     * @param array $row
     * @param Result $result
     * @return Model
     */
    public function import(array $row, Result $result) {
        $class = $this->modelClass;
        /** @var Model $model */
        if ($model = $class::find($this->getModelId($row))) {
            $result->incrementUpdated();
        } else {
            $model = new $class;
            $result->incrementInserted();
        }

        $this->upsertModel($model, $row);
        $this->upsertRelated($model, $row);

        // reset already loaded relation values
        return $model->setRelations([]);
    }

    /**
     * @param Model $model
     * @param array $row
     */
    protected function upsertModel(Model $model, array $row) {
        $data = $this->mapper->map($row);
        $model->forceFill($data);
        $model->save();
    }

    /**
     * @param Model $model
     * @param array $row
     */
    protected function upsertRelated(Model $model, array $row) {
        $fields = array_keys($this->conditions);
        foreach ($fields as $field) {
            if (!isset($row[$field])) {
                continue;
            }

            $relation = $model->$field();

            if ($relation instanceof HasMany || $relation instanceof MorphMany) {
                $this->processHasMany($model, $field, $row[$field]);
            } elseif ($relation instanceof BelongsToMany) {
                $this->processBelongsToMany($model, $field, $row[$field]);
            }
        }
    }

    /**
     * @param Model $model
     * @param string $field
     * @param array $relatedRows
     * @param boolean $allowDelete
     */
    protected function processHasMany(Model $model, $field, array $relatedRows, $allowDelete = true) {
        /** @var HasMany|MorphMany $relation */
        $relation = $model->$field();

        // store all imported ids
        $updateIds = [];
        foreach ($relatedRows as $relatedRow) {
            $data = $this->mappers[$field]->map($relatedRow);
            $conditions = $this->getConditions($field, $data);
            $instance = $model->$field()->firstOrNew($conditions);
            $instance->forceFill($data);
            $instance->save();
            $updateIds[] = $instance->getKey();
        }

        if ($allowDelete) {
            $relatedKeyName = $relation->getRelated()->getKeyName();
            $relation->whereNotIn($relatedKeyName, $updateIds)->each(function (Model $related) {
                $related->delete();
            });
        }
    }

    /**
     * @param Model $model
     * @param string $field
     * @param array $relatedRows
     * @param boolean $allowCreate
     */
    protected function processBelongsToMany(Model $model, $field, array $relatedRows, $allowCreate = true) {
        /** @var BelongsToMany $relation */
        $relation = $model->$field();
        $relatedModelClass = get_class($relation->getRelated());

        $updatedIds = [];

        foreach ($relatedRows as $relatedRow) {
            $data = $this->mappers[$field]->map($relatedRow);
            $conditions = $this->getConditions($field, $data);
            $relatedModel = $relatedModelClass::where($conditions)->first();

            if (!$relatedModel && !$allowCreate) {
                continue;
            }

            $relatedModel = $relatedModel ?: new $relatedModelClass;
            $relatedModel->forceFill($data);

            $pivotData = [];
            if (isset($this->pivotMappers[$field])) {
                $pivotData = $this->pivotMappers[$field]->map($relatedRow);
            }

            if (!$this->existsPivotRecord($model, $field, $relatedModel)) {
                $relation->save($relatedModel, $pivotData);
            } else if ($pivotData) {
                $relation->updateExistingPivot($relatedModel->getKey(), $pivotData);
            }

            $updatedIds[] = $relatedModel->getKey();
        }

        $relatedKeyName = $relation->getQualifiedRelatedPivotKeyName();
        $relatedKeyName = explode('.', $relatedKeyName);
        $relatedKeyName = end($relatedKeyName);
        $notUpdated = $relation->whereNotIn($relatedKeyName, $updatedIds)->get();
        if (!$notUpdated->isEmpty()) {
            $relation->detach($notUpdated);
        }
    }

    /**
     * @param Model $model
     * @param string $field
     * @param Model $relatedModel
     * @return bool
     */
    protected function existsPivotRecord(Model $model, $field, Model $relatedModel) {
        $relation = $model->$field();
        $relatedKeyName = $relation->getQualifiedRelatedPivotKeyName();
        $relatedKeyName = explode('.', $relatedKeyName);
        $relatedKeyName = end($relatedKeyName);

        return $relation->wherePivot($relatedKeyName, $relatedModel->getKey())->exists();
    }

    /**
     * @param string $field
     * @param array $data
     * @return array
     */
    protected function getConditions($field, array $data) {
        $conditions = array_flip($this->conditions[$field]);
        foreach ($conditions as $name => $condition) {
            $conditions[$name] = $data[$name];
        }

        return $conditions;
    }

    /**
     * @param array $row
     * @return array
     */
    protected function getData(array $row) {
        return $this->mapper->map($row);
    }
}