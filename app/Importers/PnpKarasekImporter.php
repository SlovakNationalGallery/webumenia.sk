<?php

namespace App\Importers;

use App\ImportRecord;
use App\Item;
use Illuminate\Support\Str;

class PnpKarasekImporter extends AbstractImporter
{
    protected $mapping = [
        'author' => 'Autor:',
        'date_earliest' => 'Rok od:',
        'date_latest' => 'Rok do:',
        'identifier' => 'Inventární číslo:',
        'title:sk' => 'Název:',
        'title:cs' => 'Název:',
        'dating:sk' => 'Datace:',
        'dating:cs' => 'Datace:',
        'measurement:sk' => 'Rozměry:',
        'measurement:cs' => 'Rozměry:',
        'inscription:sk' => 'Značení:',
        'inscription:cs' => 'Značení:',
        'description:sk' => 'Popis:',
        'description:cs' => 'Popis:',
    ];

    protected $defaults = [
        'gallery:sk' => 'Památník národního písemnictví, PNP',
        'gallery:cs' => 'Památník národního písemnictví, PNP',
    ];

    protected $workTypeReplacements = [
        'Malba' => 'malířství',
        'Socha' => 'sochařství',
    ];

    protected $counter;

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

    protected function importSingle(array $record, ImportRecord $import_record): Item
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
        $slug = $this->getSlug($record['Inventární číslo:']);
        return sprintf('%s(_.*)?', preg_quote($slug));
    }

    protected function getSlug($identifier)
    {
        return strtr($identifier, ' ', '_');
    }

    protected function hydrateWorkType(array $record, $locale)
    {
        $workType =
            $this->workTypeReplacements[$record['Výtvarný druh:']] ?? $record['Výtvarný druh:'];
        return $this->translateSingle($workType, 'work_type', $locale);
    }

    protected function hydrateTopic(array $record, $locale)
    {
        return $this->translateMultiple($record['Námět:'], 'topic', $locale);
    }

    protected function hydrateMedium(array $record, $locale)
    {
        return $this->translateSingle($record['Materiál:'], 'medium', $locale);
    }

    protected function hydrateTechnique(array $record, $locale)
    {
        return $this->translateMultiple($record['Technika:'], 'technique', $locale);
    }

    protected function hydrateAdditionals(array $record, $locale)
    {
        if ($locale !== 'cs') {
            return null;
        }

        return [
            'set' => $record['Soubor:'],
            'category' => $record['Kategorie:'],
            'author_birth_year' => $record['Datum narození:'],
            'author_death_year' => $record['Datum úmrtí:'],
            'author_alternative_name' => $record['Alternatívni jméno:'],
            'author_role' => $record['Role:'],
            'order' => $this->counter,
            'frontend' => ['karasek.pamatniknarodnihopisemnictvi.cz'],
        ];
    }

    protected function translateSingle($single, $map, $locale)
    {
        if ($single === null) {
            return null;
        }

        $single = Str::of($single)
            ->lower()
            ->trim();

        if ($locale === 'cs') {
            return $single;
        }

        if (!$this->translator->hasForLocale("importer.cs.$map.$single", $locale)) {
            return null;
        }

        return $this->translator->get("importer.cs.$map.$single", [], $locale);
    }

    protected function translateMultiple($multiple, $map, $locale)
    {
        if ($multiple === null) {
            return null;
        }

        return Str::of($multiple)
            ->explode(',')
            ->map(function ($single) use ($map, $locale) {
                return $this->translateSingle($single, $map, $locale);
            })
            ->reject(function ($single) {
                return $single === null;
            })
            ->implode('; ');
    }
}
