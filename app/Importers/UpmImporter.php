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
        'inscription:cs' => 'Značení',
        'inscription:sk' => 'Značení',
        'related_work:sk' => 'Sbírka',
        'related_work:cs' => 'Sbírka',
        'acquisition_date' => 'Datum akvizice',
        'exhibition' => 'Výstava',
    ];

    protected $defaults = [
        'relationship_type:sk' => 'zbierka',
        'relationship_type:cs' => 'sbírka',
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

    private array $workTypeTranslationKeys;
    private array $techniqueTranslationKeys;
    private array $mediumTranslationKeys;
    private array $objectTypeTranslationKeys;
    private array $topicTranslationKeys;


    protected function init()
    {
        $this->sanitizers[] = function ($value) {
            return empty_to_null(trim($value));
        };

        $this->workTypeTranslationKeys = array_flip(trans('item.work_types', locale: 'cs'));
        $this->techniqueTranslationKeys = array_flip(trans('item.techniques', locale: 'cs'));
        $this->mediumTranslationKeys = array_flip(trans('item.media', locale: 'cs'));
        $this->objectTypeTranslationKeys = array_flip(trans('item.object_types', locale: 'cs'));
        $this->topicTranslationKeys = array_flip(trans('item.topics', locale: 'cs'));
    }

    protected function getItemId(array $record)
    {
        return 'CZE:UPM.' . str($record['ID'])
            ->replace(' ', '')
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
            ->replace(' ', '')
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

    protected function hydrateWorkType(array $record, string $locale): ?string
    {
        if ($locale === 'cs') {
            return $record['Výtvarný druh'];
        }

        return str($record['Výtvarný druh'])
            ->split('/\s*;\s*/')
            ->map(function (string $workType) use ($locale) {
                $key = $this->workTypeTranslationKeys[$workType] ?? null;
                return $key ? trans("item.work_types.$key", locale: $locale) : null;
            })
            ->filter()
            ->join('; ') ?: null;
    }

    protected function hydrateTechnique(array $record, string $locale): ?string
    {
        if ($locale === 'cs') {
            return $record['Technika'];
        }

        return str($record['Technika'])
            ->split('/\s*;\s*/')
            ->map(function (string $technique) use ($locale) {
                $key = $this->techniqueTranslationKeys[$technique] ?? null;
                return $key ? trans("item.techniques.$key", locale: $locale) : null;
            })
            ->filter()
            ->join('; ') ?: null;
    }

    protected function hydrateMedium(array $record, string $locale): ?string
    {
        if ($locale === 'cs') {
            return $record['Materiál'];
        }

        return str($record['Materiál'])
            ->split('/\s*;\s*/')
            ->map(function (string $medium) use ($locale) {
                $key = $this->mediumTranslationKeys[$medium] ?? null;
                return $key ? trans("item.media.$key", locale: $locale) : null;
            })
            ->filter()
            ->join('; ') ?: null;
    }

    protected function hydrateObjectType(array $record, string $locale): ?string
    {
        if ($locale === 'cs') {
            return $record['Typ'];
        }

        return str($record['Typ'])
            ->split('/\s*;\s*/')
            ->map(function (string $objectType) use ($locale) {
                $key = $this->objectTypeTranslationKeys[$objectType] ?? null;
                return $key ? trans("item.object_types.$key", locale: $locale) : null;
            })
            ->filter()
            ->join('; ') ?: null;
    }

    protected function hydrateTopic(array $record, string $locale): ?string
    {
        if ($locale === 'cs') {
            return $record['Námět'];
        }

        return str($record['Námět'])
            ->split('/\s*;\s*/')
            ->map(function (string $topic) use ($locale) {
                $key = $this->topicTranslationKeys[$topic] ?? null;
                return $key ? trans("item.topics.$key", locale: $locale) : null;
            })
            ->filter()
            ->join('; ') ?: null;
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