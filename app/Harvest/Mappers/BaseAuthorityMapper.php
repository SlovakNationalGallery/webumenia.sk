<?php

namespace App\Harvest\Mappers;

use App\Authority;

class BaseAuthorityMapper extends AbstractModelMapper
{
    protected $modelClass = Authority::class;

    public function mapId(array $row) {
        if (isset($row['id'][0])) {
            return (int)$this->parseId($row['id'][0]);
        }

        return null;
    }
}