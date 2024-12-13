<?php

namespace App\Importers;

use App\Enums\FrontendEnum;

class UpmImporter extends AbstractImporter
{
    protected $mapping = [
        'identifier' => 'Inventárníčíslo',
        'title:cs' => 'Název',
        'title:sk' => 'Název',
        'title:en' => 'Název EN',
        'dating:cs' => 'Datace',
        'dating:sk' => 'Datace',
        'date_earliest' => 'Od',
        'date_latest' => 'Do',
        'work_type:cs' => 'Výtvarný druh',
        'work_type:sk' => 'Výtvarný druh',
        'object_type:cs' => 'Typ',
        'object_type:sk' => 'Typ',
        'medium:cs' => 'Materiál',
        'medium:sk' => 'Materiál',
        'technique:cs' => 'Technika',
        'technique:sk' => 'Technika',
        'topic:cs' => 'Námět',
        'topic:sk' => 'Námět',
        'inscription:cs' => 'Značení',
        'inscription:sk' => 'Značení',
        'related_work:sk' => 'Sbírka',
        'related_work:cs' => 'Sbírka',
        'acquisition_date' => 'Datum akvizice',
    ];

    protected $defaults = [
        'relationship_type:sk' => 'zo súboru',
        'relationship_type:cs' => 'ze souboru',
        'relationship_type:en' => 'collection',
        'gallery:cs' => 'Uměleckoprůmyslové museum v Praze, UPM',
        'gallery:sk' => 'Umeleckopriemyselné múzeum v Prahe, UPM',
        'frontends' => [
            FrontendEnum::UPM,
            FrontendEnum::WEBUMENIA,
        ],
    ];

    protected static $options = [
        'delimiter' => ';',
        'enclosure' => '"',
        'escape' => '\\',
        'newline' => "\n",
    ];

    protected function init()
    {
        $this->sanitizers[] = function ($value) {
            return empty_to_null(trim($value));
        };
    }

    protected function getItemId(array $record)
    {
        return 'CZE:UPM.' . str($record['ID'])
            ->explode('_')
            ->transform(fn ($part) => str($part)
                ->replaceMatches('/\W/', '-')
                ->trim('-')
            )
            ->join('_');
    }

    protected function getItemImageFilenameFormat(array $record): string
    {
        return str($record['ID'])
            ->explode('_')
            ->transform(fn ($part) => '0*' . preg_quote($part))
            ->join('_') . '(_.*)?';
    }

    protected function hydrateAuthor(array $record): string
    {
        $authors = str($record['Autor']);
        if ($authors->isEmpty()) {
            return 'Neznámý autor';
        }

        return $authors
            ->split('/\s*;\s*/')
            ->map(function (string $author) {
                preg_match('/^(?<name>[^–]*)(\s–\s(?<role>.*))?$/', $author, $matches);

                if (!isset($matches['name'], $matches['role'])) {
                    return $author;
                }

                return sprintf('%s – %s', formatName($matches['name']), $matches['role']);
            })
            ->join('; ');
    }

    protected function hydratePlace(array $record, string $locale): ?string
    {
        if (!in_array($locale, ['cs', 'sk'])) {
            return null;
        }

        $place = str($record['Vznik'])->match('/^([^;]+)/');
        return $place->isNotEmpty() ? $place->toString() : null;
    }

    protected function hydrateAdditionals(array $record, string $locale): ?array
    {
        if ($locale !== 'cs') {
            return null;
        }

        $additionals = [];

        if ($record['Způsob akvizice'] !== null) {
            $additionals['acquisition'] = $record['Způsob akvizice'];
        }

        if ($record['Výstava'] !== null) {
            $additionals['exhibition'] = $record['Výstava'];
        }

        $producer = str($record['Vznik'])->match('/;(.+)/');
        if ($producer->isNotEmpty()) {
            $additionals['producer'] = $producer->toString();
        }

        return $additionals ?: null;
    }

    protected function hydrateMeasurement(array $record, $locale): ?string
    {
        if (empty($record['Rozměry'])) {
            return null;
        }

        $replacements = trans('item.measurement_replacements', [], $locale);
        return strtr($record['Rozměry'], $replacements);
    }
}