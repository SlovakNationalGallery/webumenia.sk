<?php

namespace App\Importers;

class PnpImporter extends AbstractImporter
{
    protected $mapping = [
        'title:sk' => 'Název:',
        'author' => 'Autor:',
        'dating:sk' => 'Datace:',
        'date_earliest' => 'Rok - od',
        'date_latest' => 'Rok - do',
        'measurement:sk' => 'Rozměry:',
        'work_type:sk' => 'Výtvarný druh:',
        'topic:sk' => 'Námět:',
        'medium:sk' => 'Materiál:',
        'technique:sk' => 'Technika:',
        'identifier' => 'Inventární číslo:',
        'inscription:sk' => 'Značení:',
        'description:sk' => ' Popis:',
    ];

    protected $defaults = [
        'gallery:sk' => 'Památník národního písemnictví, PNP',
    ];

    protected function getItemId(array $record)
    {
        $id = strtr($record['Inventární číslo:'], ' /', '_-');
        return sprintf('CZE:PNP-%s', $id);
    }

    protected function getItemImageFilenameFormat(array $record): string
    {
        $id = strtr($record['Inventární číslo:'], ' /', '_-');
        return preg_quote(sprintf('PNP--%s', $id));
    }
}
