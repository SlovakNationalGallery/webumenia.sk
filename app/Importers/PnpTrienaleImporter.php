<?php

namespace App\Importers;

use App\ImportRecord;
use App\Item;
use Illuminate\Support\Str;

class PnpTrienaleImporter extends AbstractImporter
{
    protected $mapping = [
        'author' => 'Autor:',
        'date_earliest' => 'Rok - od',
        'date_latest' => 'Rok - do',
        'identifier' => 'Inventární číslo:',
        'title:sk' => 'Název:',
        'title:cs' => 'Název:',
        'dating:sk' => 'Datace:',
        'dating:cs' => 'Datace:',
        'measurement:sk' => 'Rozměry:',
        'measurement:cs' => 'Rozměry:',
        'related_work:sk' => 'Ze souboru:',
        'related_work:cs' => 'Ze souboru:',
        'description:sk' => 'Text od porotců:',
        'description:cs' => 'Text od porotců:',
    ];

    protected $defaults = [
        'relationship_type:sk' => 'zo súboru',
        'relationship_type:cs' => 'ze souboru',
        'relationship_type:en' => 'collection',
        'gallery:sk' => 'Památník národního písemnictví, PNP',
        'gallery:cs' => 'Památník národního písemnictví, PNP',
    ];

    protected $workTypeTranslations = [
        'sk' => [
            'grafika' => 'grafika',
        ],
        'en' => [
            'grafika' => 'graphics',
        ],
    ];

    protected $mediumTranslations = [
        'sk' => [
            'papír' => 'papier',
        ],
        'en' => [
            'papír' => 'paper',
        ],
    ];

    protected $techniqueTranslations = [
        'sk' => [
            'lept' => 'lept',
            'suchá jehla' => 'suchá ihla',
            'akvatinta' => 'akvatinta',
            'linoryt' => 'linoryt',
            'serigrafie' => 'serigrafia',
            'litografie' => 'litografia',
            'počítačová grafika' => 'počítačová grafika',
            'kombinovaná technika' => 'kombinovaná technika',
            'mezzotinta' => 'mezzotinta',
            'mědiryt' => 'mediryt',
            'šablona na batice' => 'šablóna na batike',
            'vernis mou' => 'vernis mou',
        ],
        'en' => [
            'lept' => 'etching',
            'suchá jehla' => 'drypoint',
            'akvatinta' => 'aquatint',
            'linoryt' => 'linocut',
            'serigrafie' => 'serigraphy',
            'litografie' => 'lithography',
            'počítačová grafika' => 'computer graphics',
            'kombinovaná technika' => 'combined technique',
            'mezzotinta' => 'mezzotint',
            'mědiryt' => 'chalcography',
            'šablona na batice' => 'stencil on batik',
            'vernis mou' => 'vernis mou',
        ],
    ];

    protected $counter;

    protected static $name = 'pnp_trienale';

    protected function init()
    {
        $this->sanitizers[] = function ($value) {
            return empty_to_null($value);
        };
    }

    public function import(ImportRecord $import_record, $stream): array
    {
        $this->counter = 0;
        return parent::import($import_record, $stream);
    }

    protected function importSingle(array $record, ImportRecord $import_record): ?Item
    {
        $this->counter++;
        return parent::importSingle($record, $import_record);
    }

    protected function getItemId(array $record)
    {
        return sprintf('CZE:PNP.%s', $this->getSlug($record['Inventární číslo:']));
    }

    protected function getItemImageFilenameFormat(array $record): string
    {
        return $this->getSlug($record['Inventární číslo:']);
    }

    protected function getSlug($identifier)
    {
        return preg_replace('#^(\d+)/(\d+)/([A-Z]+)\s+-\s+(\d+)#', '$1_$2_$3-$4', $identifier);
    }

    public function hydrateWorkType(array $record, $locale)
    {
        $workType = Str::lower($record['Výtvarný druh:']);

        if ($locale === 'cs') {
            return $workType;
        }

        return $this->workTypeTranslations[$locale][$workType];
    }

    public function hydrateMedium(array $record, $locale)
    {
        $medium = Str::lower($record['Materiál:']);

        if ($locale === 'cs') {
            return $medium;
        }

        return $this->mediumTranslations[$locale][$medium];
    }

    public function hydrateTechnique(array $record, $locale)
    {
        $techniques = explode(',', Str::lower($record['Technika:']));
        $techniques = array_map('trim', $techniques);

        if ($locale !== 'cs') {
            $techniques = array_map(function ($technique) use ($locale) {
                return $this->techniqueTranslations[$locale][$technique];
            }, $techniques);
        }

        return implode(';', $techniques);
    }

    public function hydrateAdditionals(array $record, $locale)
    {
        if ($locale !== 'cs') {
            return null;
        }

        return [
            'award' => $record['Ocenění:'],
            'award_category' => $record['Kategorie:'],
            'order' => $this->counter,
        ];
    }
}
