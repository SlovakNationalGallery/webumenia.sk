<?php

namespace App\Importers;

use App\Repositories\IFileRepository;
use Illuminate\Support\Str;

class Pnp65OUSImporter extends AbstractImporter
{
    protected $options = [
        'delimiter' => ',',
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
        'Autorská práva' => 'license'
    ];

    protected static $license_replacements = [
        'volné dílo' => 'free_download',
        'autorsky chráněné dílo' => 'only_zoom'
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

    protected function hydrateLicense(array $record) {
        return (isset(static::$license_replacements[$record['Autorská práva']])) ? static::$license_replacements[$record['Autorská práva']] : null;
    }

    protected function sanitizeIdentifier($identifier) {
        $identifier = str_replace(array(' ', '/'), '_', $identifier);
        return Str::ascii($identifier);
    }
}