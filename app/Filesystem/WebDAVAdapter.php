<?php

namespace App\Filesystem;

use League\Flysystem\FileAttributes;
use League\Flysystem\WebDAV\WebDAVAdapter as BaseWebDAVAdapter;

/**
 * This class is a workaround for the issue of handling spaces in paths
 */
class WebDAVAdapter extends BaseWebDAVAdapter
{
    public function lastModified(string $path): FileAttributes
    {
        $path = $this->encodePath($path);
        return parent::lastModified($path);
    }
}