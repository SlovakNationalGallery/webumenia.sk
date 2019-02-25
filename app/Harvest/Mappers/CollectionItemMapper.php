<?php

namespace App\Harvest\Mappers;

use App\Collection;

class CollectionItemMapper extends AbstractModelMapper
{
    protected $modelClass = Collection::class;

    public function mapId(array $row) {
        return $row['id'];
    }
}