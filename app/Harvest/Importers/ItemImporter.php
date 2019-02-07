<?php

namespace App\Harvest\Importers;

use App\Harvest\Mappers\ItemImageMapper;
use App\Harvest\Mappers\CollectionMapper;
use App\Harvest\Mappers\ItemMapper;
use App\Item;

class ItemImporter extends AbstractImporter
{
    protected $modelClass = Item::class;

    protected $conditions = [
        'images' => ['iipimg_url'],
        'collections' => ['id'],
    ];

    public function __construct(
        ItemMapper $mapper,
        ItemImageMapper $itemImageMapper,
        CollectionMapper $collectionMapper
    ) {
        parent::__construct($mapper);
        $this->mappers = [
            'images' => $itemImageMapper,
            'collections' => $collectionMapper,
        ];
    }

    public function getModelId(array $row) {
        return $this->mapper->mapId($row);
    }
}