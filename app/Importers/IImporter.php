<?php

namespace App\Importers;

use App\Import;
use App\Item;
use SplFileInfo;

interface IImporter
{
    /**
     * @return Item[]
     */
    public function import(Import $import, SplFileInfo $file);

    /**
     * @return string
     */
    public static function getName();

    /**
     * @return array
     */
    public static function getOptions();
}
