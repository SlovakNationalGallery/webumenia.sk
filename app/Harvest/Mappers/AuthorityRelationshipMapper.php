<?php

namespace App\Harvest\Mappers;

use App\AuthorityRelationship;

class AuthorityRelationshipMapper extends AbstractMapper
{
    protected $modelClass = AuthorityRelationship::class;

    public function mapType(array $row) {
        if (isset($row['type'][0])) {
            $type = $row['type'][0];
            return $this->chooseTranslation($type, 'sk');
        }
    }
}