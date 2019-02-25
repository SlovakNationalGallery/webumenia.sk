<?php


namespace App\Harvest\Mappers;


use App\Authority;

class AuthorityItemMapper extends AbstractModelMapper
{
    protected $modelClass = Authority::class;

    protected function mapId(array $row) {
        return (int)$this->parseId($row['id'][0]);
    }
}