<?php

namespace App\Harvest\Importers;

use App\Harvest\Importers\AbstractImporter;
use App\Harvest\Mappers\GmuhkItemMapper;
use App\Harvest\Progress;
use App\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class GmuhkItemImporter extends AbstractImporter
{
    protected $modelClass = Item::class;

    public function __construct(GmuhkItemMapper $mapper) {
        parent::__construct($mapper);
    }

    public function getModelId(array $row) {
        return $this->mapper->mapId($row);
    }

    public function import(array $row, Progress $result) {
        $item = parent::import($row, $result);

        if (!empty($row['image'][0])) {
            $url = $this->encodeUrl($row['image'][0]);
            $lastModified = $this->getLastModified($url);
            if (!$lastModified || $item->getImageModificationDateTime() < $lastModified) {
                $image = $item->saveImage($url);
                touch(
                    $image->basePath(),
                    $lastModified ? $lastModified->getTimestamp() : time()
                );
            }
        }

        return $item;
    }

    protected function getLastModified($url)
    {
        $lastModified = get_headers($url, true)['Last-Modified'] ?? null;
        return $lastModified ? Date::create($lastModified) : null;
    }

    protected function encodeUrl($url)
    {
        // Item::saveImage requires encoded url
        // and parse_url does not support utf8
        $parsed = parse_url($url);
        $path = \Str::of($url)
            ->after($parsed['host'])
            ->explode('/')
            ->transform(function ($part) {
                return rawurlencode($part);
            })
            ->implode('/');
        return sprintf(
            '%s://%s%s',
            $parsed['scheme'],
            $parsed['host'],
            $path,
        );
    }
}