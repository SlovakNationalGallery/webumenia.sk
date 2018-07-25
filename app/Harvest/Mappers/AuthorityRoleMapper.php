<?php

namespace App\Harvest\Mappers;

use App\AuthorityRole;

class AuthorityRoleMapper extends AbstractModelMapper
{
    protected $modelClass = AuthorityRole::class;

    public function mapRole(array $row, $locale) {
        if (isset($row['role'][0])) {
            $role = $row['role'][0];
            return $this->chooseTranslation($role, $locale);
        }
    }
}