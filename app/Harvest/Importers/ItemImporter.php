<?php

namespace App\Harvest\Importers;

use App\Harvest\Mappers\AuthorityItemMapper;
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
        AuthorityItemMapper $authorityItemMapper
    ) {
        parent::__construct($mapper);
        $this->mappers = [
            'images' => $itemImageMapper,
            'collections' => $collectionItemMapper,
            'authorities' => $authorityItemMapper,
        ];
    }

    public function getModelId(array $row) {
        return $this->mapper->mapId($row);
    }

    protected function processBelongsToMany(Model $model, $field, array $relatedRows, $createRelated = true) {
        $createRelated &= !in_array($field, ['authorities', 'collections']);
        parent::processBelongsToMany($model, $field, $relatedRows, $createRelated);
    }
}