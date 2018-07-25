<?php

namespace App\Harvest\Mappers;

use App\Link;

class LinkMapper extends AbstractModelMapper
{
    protected $modelClass = Link::class;

    public function mapUrl(array $row) {
        return $row['url'];
    }

    public function mapLabel(array $row) {
        if (isset($row['url'][0])) {
            $urlParts = parse_url($row['url'][0]);
            return $urlParts['host'];
        }
    }
}