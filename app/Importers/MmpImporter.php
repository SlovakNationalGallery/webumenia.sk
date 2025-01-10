<?php

namespace App\Importers;

use Illuminate\Support\Facades\Date;

class MmpImporter extends AbstractImporter
{
    protected $mapping = [
        'identifier' => 'Inventární číslo',
        'title' => 'Titul',
        'dating' => 'Datace vzniku',
        'inscription' => 'Signatura',
    ];

    protected $defaults = [
        'gallery:sk' => 'Múzeum Prahy, MP',
        'gallery:cs' => 'Muzeum Prahy, MP',
    ];

    private array $workTypeTranslationKeys;
    private array $techniqueTranslationKeys;
    private array $mediumTranslationKeys;
    private array $topicTranslationKeys;

    protected function init(): void
    {
        $this->sanitizers[] = function ($value) {
            return empty_to_null(trim($value));
        };

        $this->workTypeTranslationKeys = array_flip(trans('item.work_types', locale: 'cs'));
        $this->techniqueTranslationKeys = array_flip(trans('item.techniques', locale: 'cs'));
        $this->mediumTranslationKeys = array_flip(trans('item.media', locale: 'cs'));
        $this->topicTranslationKeys = array_flip(trans('item.topics', locale: 'cs'));
    }

    protected function getItemId(array $record): string
    {
        return str($record['Inventární číslo'])
            ->swap([
                ' ' => '_',
                '/' => '-',
            ])
            ->prepend('CZE:MP.');
    }

    protected function getItemImageFilenameFormat(array $record): string
    {
        return str($record['Inventární číslo'])
            ->replace('/', '_')
            ->append('(-.*)?');
    }

    protected function hydrateAuthor(array $record): string
    {
        if ($record['Autor'] === 'Anonym') {
            return 'Neznámy autor';
        }

        return str($record['Autor'])
            ->trim()
            ->replaceMatches('/(.*?) /', '$1, ', 1);
    }

    protected function hydrateWorkType(array $record, string $locale): ?string
    {
        $replacements = [
            'malba' => 'malířství',
        ];

        return str($record['Výtvarný druh'])
            ->split('/\s*;\s*/')
            ->map(fn (string $workType) => $replacements[$workType] ?? $workType)
            ->when($locale !== 'cs', fn ($workTypes) => $workTypes->map(function (string $workType) use ($locale) {
                $key = $this->workTypeTranslationKeys[$workType] ?? null;
                return $key ? trans("item.work_types.$key", locale: $locale) : null;
            }))
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

    protected function hydrateTopic(array $record, string $locale): ?string
    {
        if ($locale === 'cs') {
            return $record['Námět/téma'];
        }

        return str($record['Námět/téma'])
            ->split('/\s*;\s*/')
            ->map(function (string $topic) use ($locale) {
                $key = $this->topicTranslationKeys[$topic] ?? null;
                return $key ? trans("item.topics.$key", locale: $locale) : null;
            })
            ->filter()
            ->join('; ') ?: null;
    }

    protected function hydrateMeasurement(array $record, $locale): ?string
    {
        if (empty($record['Rozměr'])) {
            return null;
        }

        $replacements = trans('item.measurement_replacements', [], $locale);
        return strtr($record['Rozměr'], $replacements);
    }

    protected function hydrateDateEarliest(array $record): int
    {
        return Date::createFromFormat('d.m.Y', $record['(n) Datace OD'])->year;
    }

    protected function hydrateDateLatest(array $record): int
    {
        return Date::createFromFormat('d.m.Y', $record['(n) Datace DO'])->year;
    }
}