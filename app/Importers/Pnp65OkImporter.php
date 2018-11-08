<?php

namespace App\Importers;

use App\Repositories\IFileRepository;
use Illuminate\Support\Str;

class Pnp65OkImporter extends AbstractImporter
{
    protected $options = [
        'delimiter' => ';',
        'newline' => "\r\n",
        'input_encoding' => 'CP1250',
    ];

    protected $mapping = [
        'Název' => 'title',
        'Nakladatel' => 'publisher',
        'Tiskárna' => 'printer',
        'Rok vydání' => 'dating',
        'od' => 'date_earliest',
        'do' => 'date_latest',
        'Vazba' => 'medium',
        'Provenience' => 'related_work',
        'Popis' => 'description',
        'Autor textu' => 'description_source',
        'Signatura' => 'identifier',
    ];

    protected static $name = 'pnp65ok';

    public function __construct(IFileRepository $repository) {
        parent::__construct($repository);
        $this->filters[] = function (array $record) {
            return $record['Signatura'] !== '';
        };
    }

    protected function getItemId(array $record) {
        return sprintf('CZE:PNP.%s',  $this->sanitizeIdentifier($record['Signatura']));
    }

    protected function getItemImageFilenameFormat(array $record) {
        return sprintf('%s--*', $this->sanitizeIdentifier($record['Signatura']));
    }

    protected function hydrateAuthor(array $record) {
        // @todo other authors
        return $record['Autor'];
    }

    protected function sanitizeIdentifier($identifier) {
        $identifier = str_replace(' ', '_', $identifier);
        return Str::ascii($identifier);
    }
}