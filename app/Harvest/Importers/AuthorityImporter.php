<?php

namespace App\Harvest\Importers;

use App\Authority;
use App\AuthorityRelationship;
use App\Harvest\Mappers\AuthorityEventMapper;
use App\Harvest\Mappers\AuthorityMapper;
use App\Harvest\Mappers\AuthorityNameMapper;
use App\Harvest\Mappers\AuthorityNationalityMapper;
use App\Harvest\Mappers\AuthorityRelationshipMapper;
use App\Harvest\Mappers\LinkMapper;
use App\Harvest\Mappers\NationalityMapper;
use App\Harvest\Mappers\RelatedAuthorityMapper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AuthorityImporter extends AbstractImporter
{
    protected $modelClass = Authority::class;

    protected $conditions = [
        'events' => ['event', 'place', 'start_date', 'end_date'],
        'names' => ['name'],
        'links' => ['url'],
        'nationalities' => ['id'],
        'relationships' => ['id'],
    ];

    protected $forceReplace = [
        'events',
        'names',
    ];

    public function __construct(
        AuthorityMapper $mapper,
        AuthorityEventMapper $authorityEventMapper,
        AuthorityNameMapper $authorityNameMapper,
        AuthorityNationalityMapper $authorityNationalityMapper,
        AuthorityRelationshipMapper $authorityRelationshipMapper,
        LinkMapper $linkMapper,
        NationalityMapper $nationalityMapper,
        RelatedAuthorityMapper $relatedAuthorityMapper
    ) {
        parent::__construct($mapper);
        $this->mappers = [
            'events' => $authorityEventMapper,
            'names' => $authorityNameMapper,
            'links' => $linkMapper,
            'nationalities' => $nationalityMapper,
            'relationships' => $relatedAuthorityMapper,
        ];
        $this->pivotMappers = [
            'nationalities' => $authorityNationalityMapper,
            'relationships' => $authorityRelationshipMapper,
        ];
    }

    public function getModelId(array $row) {
        return $this->mapper->mapId($row);
    }

    protected function upsertModel(Model $model, array $row) {
        if ($model->exists) {
            unset($row['biography']);
        }

        parent::upsertModel($model, $row);
    }

    protected function processBelongsToMany(Model $model, $field, array $relatedRows, $createRelated = true) {
        $createRelated &= !in_array($field, ['relationships']);
        parent::processBelongsToMany($model, $field, $relatedRows, $createRelated);
    }

    protected function existsPivotRecord(Model $model, $field, Model $relatedModel) {
        if ($field !== 'relationships') {
            return parent::existsPivotRecord($model, $field, $relatedModel);
        }

        /** @var BelongsToMany $relation */
        $relation = $model->$field();

        $foreignKeyName = $relation->getForeignKey();
        $foreignKeyName = explode('.', $foreignKeyName);
        $foreignKeyName = end($foreignKeyName);
        $otherKeyName = $relation->getOtherKey();
        $otherKeyName = explode('.', $otherKeyName);
        $otherKeyName = end($otherKeyName);

        return AuthorityRelationship::where([
            $foreignKeyName => $model->getKey(),
            $otherKeyName => $relatedModel->getKey(),
        ])->exists();
    }
}