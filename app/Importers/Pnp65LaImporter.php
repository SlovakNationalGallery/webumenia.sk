<?php

namespace App\Importers;

class Pnp65LaImporter extends AbstractImporter
{
    protected $options = [
        'delimiter' => '|',
    ];

    protected $mapping = [
        'Název' => 'title',
        'Autor' => 'author',
        'Datace' => 'dating',
        'RokOd' => 'date_earliest',
        'Do' => 'date_latest',
        'Typ archiválie' => 'work_type',
        'Technika' => 'technique',
        'Rozměry' => 'measurement',
        'Ze souboru' => 'related_work',
        ' Popis' => 'description',
    ];

    protected static $name = 'pnp65la';

    protected function getItemId(array $record) {
        return sprintf('CZE:PNP.%s',  $record['IDENTIFIKAČNÍ ČÍSLO']);
    }

    protected function getItemImageFilenameFormat(array $record) {
        return sprintf('{%1$s,%1$s-*}', $record['IDENTIFIKAČNÍ ČÍSLO']);
    }
}