<?php

namespace App\Harvest\Mappers;

use App\AuthorityRelationship;

class AuthorityRelationshipMapper extends AbstractModelMapper
{
    protected $modelClass = AuthorityRelationship::class;

    public function mapType(array $row, $locale) {
        if (isset($row['type'][0])) {
            $type = $row['type'][0];
            return $this->chooseTranslation($type, $locale);
        }
    }
}