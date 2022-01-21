<?php

namespace App\Harvest\Mappers;

class AuthorityItemMapper extends AbstractMapper
{
    protected function mapRole(array $row) {
        if (isset($row['role'][0])) {
            return $row['role'][0];
        }
    }
}