<?php

namespace App\Harvest\Harvesters;

use App\Harvest\Importers\ItemImporter;
use App\Harvest\Importers\Result;
use App\Harvest\Repositories\ItemRepository;
use App\Item;
use App\SpiceHarvesterRecord;
use Illuminate\Support\Str;
use Monolog\Logger;

class ItemHarvester extends AbstractHarvester
{
    /** @var array */
    protected $excludePrefix = ['x'];

    /** @var Logger */
    protected $logger;

    public function __construct(ItemRepository $repository, ItemImporter $importer) {
        parent::__construct($repository, $importer);
        $this->logger = new Logger('oai_harvest');
    }

    public function harvestSingle(SpiceHarvesterRecord $record, Result $result, array $row = null) {
        if ($row === null) {
            $row = $this->repository->getRow($record);
        }

        // @todo responsibility of repository?
        $imgUrl = $this->getItemImageImgUrl($row);
        $iipimgUrls = $this->fetchItemImageIipimgUrls($row);
        $iipimgUrls = $this->parseItemImageIipimgUrls($iipimgUrls);

        if ($iipimgUrls) {
            $row['images'] = [];
            foreach ($iipimgUrls as $iipimgUrl) {
                $row['images'][] = [
                    'img_url' => [$imgUrl],
                    'iipimg_url' => [$iipimgUrl],
                ];
            }
        } else {
            $row['images'][] = [
                'img_url' => [$imgUrl],
                'iipimg_url' => [],
            ];
        }

        if ($record->harvest->collection) {
            $row['collections'] = [
                ['id' => $record->harvest->collection->id],
            ];
        }

        $model = parent::harvestSingle($record, $result, $row);
        if ($model) {
            $this->downloadImages($model);
        }

        return $model;
    }

    protected function isExcluded(array $row) {
        $id = $this->importer->getModelId($row);
        $prefix = substr($id, strpos($id, '.') + 1, 1);
        $prefix = Str::lower($prefix);
        return in_array($prefix, $this->excludePrefix);
    }

    /**
     * @param Item $item
     */
    protected function downloadImages(Item $item) {
        foreach ($item->images as $image) {
            if (!$image->img_url) {
                continue;
            }

            try {
                $url = $image->img_url;
                $imageData = file_get_contents($url);
                if ($path = $item->getImagePath($full = true)) {
                    file_put_contents($path, $imageData);
                }
            } catch (\Exception $e) {
                $error = sprintf('%s: %s', $url, $e->getMessage());
                $this->logger->addError($error);
            }
        }
    }


    /**
     * @param array $row
     * @return string
     */
    protected function getItemImageImgUrl(array $row) {
        return array_first($row['identifier'], function ($i, $identifier) {
            return str_contains($identifier, 'getimage');
        });
    }

    /**
     * @param array $row
     * @return string|bool
     */
    protected function fetchItemImageIipimgUrls(array $row) {
        $url = array_first($row['identifier'], function ($i, $identifier) {
            return str_contains($identifier, 'L2_WEB');
        });

        if ($url === null) {
            return false;
        }

        return @file_get_contents($url);
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
}