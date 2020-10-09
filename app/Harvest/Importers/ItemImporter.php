<?php

namespace App\Harvest\Importers;

use App\Harvest\Mappers\AuthorityItemMapper;
use App\Harvest\Mappers\BaseAuthorityMapper;
use App\Harvest\Mappers\ItemImageMapper;
use App\Harvest\Mappers\CollectionItemMapper;
use App\Harvest\Mappers\ItemMapper;
use App\Item;
use Illuminate\Database\Eloquent\Model;

class ItemImporter extends AbstractImporter
{
    protected $modelClass = Item::class;

    protected $conditions = [
        'images' => ['iipimg_url'],
        'collections' => ['id'],
        'authorities' => ['id'],
    ];

    public function __construct(
        ItemMapper $mapper,
        ItemImageMapper $itemImageMapper,
        CollectionItemMapper $collectionItemMapper,
        AuthorityItemMapper $authorityItemMapper,
        BaseAuthorityMapper $authorityMapper
    ) {
        parent::__construct($mapper);
        $this->mappers = [
            'images' => $itemImageMapper,
            'collections' => $collectionItemMapper,
            'authorities' => $authorityMapper,
        ];
        $this->pivotMappers = [
            'authorities' => $authorityItemMapper,
        ];
    }

    public function getModelId(array $row) {
        return $this->mapper->mapId($row);
    }

    protected function processHasMany(Model $model, $field, array $relatedRows, $allowDelete = true) {
        $allowDelete &= !in_array($field, ['images']);
        parent::processHasMany($model, $field, $relatedRows, $allowDelete);
    }

    protected function processBelongsToMany(Model $model, $field, array $relatedRows, $allowCreate = true) {
        $allowCreate &= !in_array($field, ['authorities', 'collections']);
        parent::processBelongsToMany($model, $field, $relatedRows, $allowCreate);
    }
}