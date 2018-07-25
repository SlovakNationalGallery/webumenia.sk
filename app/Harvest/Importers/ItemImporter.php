<?php

namespace App\Harvest\Importers;

use App\Harvest\Mappers\ItemImageMapper;
use App\Harvest\Mappers\ItemMapper;
use App\Item;

class ItemImporter extends AbstractImporter
{
    protected $modelClass = Item::class;

    protected $conditions = [
        'images' => ['iipimg_url'],
        'collections' => ['id'],
    ];

    public function __construct(ItemMapper $mapper, ItemImageMapper $itemImageMapper) {
        parent::__construct($mapper);
        $this->mappers = [
            'images' => $itemImageMapper
        ];
    }

    public function getModelId(array $row) {
        return $this->mapper->mapId($row);
    }

    protected function getData(array $row) {
        $row = parent::getData($row);
        // do not overwrite existing biography
        unset($row['biography']);
        return $row;
    }
}