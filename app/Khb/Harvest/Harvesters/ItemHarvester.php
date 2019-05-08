<?php

namespace App\Khb\Harvest\Harvesters;

class ItemHarvester extends \App\Harvest\Harvesters\ItemHarvester
{
    protected function fetchItemImageIipimgUrls(array $row) {
        $iipimgUrls = glob(sprintf(
            '%s/KHB/KHB--%s--*.jp2',
            config('importers.iip_base_path'),
            str_after($row['id'][0], 'SVK:KHB.')
        ));

        return array_map(function($iipimgUrl) {
            return str_after($iipimgUrl, config('importers.iip_base_path'));
        }, $iipimgUrls);
    }
}
