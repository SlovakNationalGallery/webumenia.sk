<?php

namespace App\Importers;

use App\Repositories\IFileRepository;
use Illuminate\Support\Str;

class Pnp65OUSImporter extends AbstractImporter
{
    protected $options = [
        'delimiter' => ';',
        'newline' => "\r\n",
        'input_encoding' => 'CP1250',
    ];

    protected $mapping = [
        'Inventární číslo:' => 'identifier',
        'Autor:' => 'author',
        'Datace:' => 'dating',
        'Rok - od:' => 'date_earliest',
        'Rok - do:' => 'date_latest',
        'Výtvarný druh:' => 'work_type',
        'Název:' => 'title',
        'Materiál:' => 'medium',
        'Technika:' => 'technique',
        'Námět:' => 'topic',
        'Značení:' => 'inscription',
        'Rozměry:' => 'measurement',
        'Ze souboru:' => 'related_work',
        'Popis:' => 'description',
        'Nakladatel:' => 'publisher',
        'Autor textu:' => 'description_source',
    ];

    protected static $name = 'pnp65ous';

    public function __construct(IFileRepository $repository) {
        parent::__construct($repository);
        $this->filters[] = function (array $record) {
            return $record['Inventární číslo:'] !== '';
        };
    }

    protected function getItemId(array $record) {
        return sprintf('CZE:PNP.%s',  $this->sanitizeIdentifier($record['Inventární číslo:']));
    }

    protected function getItemImageFilenameFormat(array $record) {
        return sprintf('%s--*', $this->sanitizeIdentifier($record['Inventární číslo:']));
    }

    protected function hydrateAuthor(array $record) {
        // @todo other authors
        return $record['Autor:'];
    }

    protected function sanitizeIdentifier($identifier) {
        $identifier = str_replace(' ', '_', $identifier);
        return Str::ascii($identifier);
    }
}