<?php

namespace App\Harvest\Mappers;

use App\Authority;

class RelatedAuthorityMapper extends AbstractMapper
{
    protected $modelClass = Authority::class;

    public function mapId(array $row) {
        if (isset($row['related_authority_id'][0])) {
            return (int)$this->parseId($row['related_authority_id'][0]);
        }
    }
}