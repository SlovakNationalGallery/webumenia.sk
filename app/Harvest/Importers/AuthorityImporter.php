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

    protected function processBelongsToMany(Model $model, $field, array $relatedRows, $allowCreate = true) {
        $allowCreate &= !in_array($field, ['relationships']);
        parent::processBelongsToMany($model, $field, $relatedRows, $allowCreate);
    }

    protected function existsPivotRecord(Model $model, $field, Model $relatedModel) {
        if ($field !== 'relationships') {
            return parent::existsPivotRecord($model, $field, $relatedModel);
        }

        /** @var BelongsToMany $relation */
        $relation = $model->$field();

        $foreignKeyName = $relation->getQualifiedForeignPivotKeyName();
        $foreignKeyName = explode('.', $foreignKeyName);
        $foreignKeyName = end($foreignKeyName);
        $relatedKeyName = $relation->getQualifiedRelatedPivotKeyName();
        $relatedKeyName = explode('.', $relatedKeyName);
        $relatedKeyName = end($relatedKeyName);

        return AuthorityRelationship::where([
            $foreignKeyName => $model->getKey(),
            $relatedKeyName => $relatedModel->getKey(),
        ])->exists();
    }
}