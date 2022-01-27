<?php

namespace App\Harvest\Importers;

use App\Harvest\Importers\AbstractImporter;
use App\Harvest\Mappers\GmuhkItemMapper;
use App\Harvest\Progress;
use App\Item;
use App\Matchers\AuthorityMatcher;
use Illuminate\Support\Facades\Date;

class GmuhkItemImporter extends AbstractImporter
{
    protected $modelClass = Item::class;

    protected $authorityMatcher;

    public function __construct(GmuhkItemMapper $mapper, AuthorityMatcher $authorityMatcher) {
        parent::__construct($mapper);
        $this->authorityMatcher = $authorityMatcher;
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

        $ids = $this->authorityMatcher
            ->matchAll($item)
            ->filter(function ($authorities) {
                return $authorities->count() === 1;
            })
            ->flatten()
            ->pluck('id');

        $changes = $item->authorities()->syncWithoutDetaching($ids);
        $item->authorities()->updateExistingPivot($changes['attached'], ['automatically_matched' => true]);

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