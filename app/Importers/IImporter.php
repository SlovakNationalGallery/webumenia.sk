<?php


namespace App\Importers;

use App\Import;
use App\Item;

interface IImporter
{
    /**
     * @param Import $import
     * @param array $file
     * @return Item[]
     */
    public function import(Import $import, array $file);

    /**
     * @return string
     */
    public static function getName();
}