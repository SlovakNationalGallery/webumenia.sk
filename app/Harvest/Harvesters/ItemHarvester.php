<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\ItemImporter;
use App\Harvest\Repositories\ItemRepository;
use App\Harvest\Progress;
use App\SpiceHarvesterRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ItemHarvester extends AbstractHarvester
{
    /** @var array */
    protected $excludePrefix = ['x'];

    public function __construct(ItemRepository $repository, ItemImporter $importer) {
        parent::__construct($repository, $importer);
    }

    protected function processRecord(SpiceHarvesterRecord $record, Progress $progress, array $row) {
        // @todo responsibility of repository?
        $iipimgUrls = $this->fetchItemImageIipimgUrls($row);

        $row['images'] = [];
        foreach ($iipimgUrls as $iipimgUrl) {
            $row['images'][] = [
                'iipimg_url' => [$iipimgUrl],
            ];
        }

        if ($record->harvest->collection) {
            $row['collections'] = [
                ['id' => $record->harvest->collection->id],
            ];
        }

        parent::processRecord($record, $progress, $row);

        if ($record->item && $record->item->img_url) {
            $record->item->saveImage($record->item->img_url);
        }
    }

    protected function isExcluded(array $row) {
        $id = $this->importer->getModelId($row);
        $prefix = substr($id, strpos($id, '.') + 1, 1);
        $prefix = Str::lower($prefix);
        return in_array($prefix, $this->excludePrefix);
    }

    /**
     * @param array $row
     * @return string[]
     */
    protected function fetchItemImageIipimgUrls(array $row) {
        $url = Arr::first($row['identifier'], function ($identifier) {
            return str_contains($identifier, 'L2_WEB');
        });

        if ($url === null) {
            return [];
        }

        return $this->parseItemImageIipimgUrls(file_get_contents($url));
    }

    /**
     * @param string $iipimgUrls
     * @return string[]
     */
    protected function parseItemImageIipimgUrls($iipimgUrls) {
        $iipimgUrls = strip_tags($iipimgUrls, '<br>');
        $iipimgUrls = explode('<br>', $iipimgUrls);
        $iipimgUrls = array_filter($iipimgUrls, function ($iipimgUrl) {
            return str_contains($iipimgUrl, '.jp2');
        });

        sort($iipimgUrls);

        return array_map(function($iipimgUrl) {
            $iipimgUrl = substr($iipimgUrl, strpos($iipimgUrl, '?FIF=') + 5);
            $iipimgUrl = substr($iipimgUrl, 0, strpos($iipimgUrl, '.jp2') + 4);
            return $iipimgUrl;
        }, $iipimgUrls);
    }

    protected function isForDeletion(array $row) {
        return parent::isForDeletion($row) || (isset($row['rights'][0]) && !$row['rights'][0]);
    }
}
