<?php

namespace App\Harvest\Mappers;

use App\Nationality;

class NationalityMapper extends AbstractModelMapper
{
    protected $modelClass = Nationality::class;

    public function mapId(array $row) {
        if (isset($row['id'][0])) {
            $id = $row['id'][0];
            return (int)$this->parseId($id);
        }
    }

    public function mapCode(array $row) {
        return $row['code'];
    }
}