<?php

namespace App\Harvest\Mappers;

use App\ItemImage;

class ItemImageMapper extends AbstractMapper
{
    protected $modelClass = ItemImage::class;

    public function mapIipimgUrl(array $row) {
        if (isset($row['iipimg_url'][0])) {
            return $row['iipimg_url'][0];
        }
    }
}