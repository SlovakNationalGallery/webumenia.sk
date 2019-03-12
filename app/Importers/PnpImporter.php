<?php

namespace App\Importers;

class PnpImporter extends AbstractImporter
{
    protected $mapping = [
        'Název:' => 'title:sk',
        'Autor:' => 'author',
        'Datace:' => 'dating:sk',
        'Rok - od' => 'date_earliest',
        'Rok - do' => 'date_latest',
        'Rozměry:' => 'measurement:sk',
        'Výtvarný druh:' => 'work_type:sk',
        'Námět:' => 'topic:sk',
        'Materiál:' => 'medium:sk',
        'Technika:' => 'technique:sk',
        'Inventární číslo:' => 'identifier',
        'Značení:' => 'inscription:sk',
        ' Popis:' => 'description:sk',
    ];

    protected $defaults = [
        'gallery:sk' => 'Památník národního písemnictví, PNP',
    ];

    protected static $name = 'pnp';

    protected function getItemId(array $record) {
        $id = strtr($record['Inventární číslo:'], ' /', '_-');
        return sprintf("CZE:PNP-%s", $id);
    }

    protected function getItemImageFilenameFormat(array $record) {
        $id = strtr($record['Inventární číslo:'], ' /', '_-');
        return sprintf("PNP--%s", $id);
    }
}