<?php

namespace App\Khb\Harvest\Harvesters;

class ItemHarvester extends \App\Harvest\Harvesters\ItemHarvester
{
    protected function fetchItemImageIipimgUrls(array $row) {
        return glob(sprintf(
            '%s/KHB/KHB--%s--*.jp2',
            config('importers.iip_base_path'),
            str_after($row['id'][0], 'SVK:KHB.')
        ));
    }

    protected function parseItemImageIipimgUrls($iipimgUrls) {
        return $iipimgUrls;
    }
}
