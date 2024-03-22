<?php

namespace App\Harvest\Mappers;

use Illuminate\Support\Facades\Date;

trait MuseionTrait
{
    protected array $mediumTranslationKeys;

    protected array $techniqueTranslationKeys;

    protected array $mediumCorrectionMap = [];

    public function mapIdentifier(array $row): string
    {
        return $row['identifier'][0];
    }

    public function mapAuthor(array $row): string
    {
        return $row['author'][0];
    }

    public function mapTitle(array $row, string $locale): ?string
    {
        if (!in_array($locale, ['sk', 'cs'])) {
            return null;
        }

        return $row['title'][0];
    }

    public function mapDating(array $row, string $locale): ?string
    {
        if (!in_array($locale, ['sk', 'cs'])) {
            return null;
        }

        return $row['dating'][0] ?? null;
    }

    public function mapGallery(array $row, string $locale): ?string
    {
        if (!in_array($locale, ['sk', 'cs'])) {
            return null;
        }

        return $row['gallery'][0];
    }

    public function mapDateEarliest(array $row): ?int
    {
        if (!isset($row['date_earliest'][0])) {
            return null;
        }

        return (int) Date::create($row['date_earliest'][0])->format('Y');
    }

    public function mapDateLatest(array $row): ?int
    {
        if (!isset($row['date_latest'][0])) {
            return null;
        }

        return (int) Date::create($row['date_latest'][0])->format('Y');
    }

    public function mapTechnique(array $row, string $locale): ?string
    {
        if (!isset($row['technique'][0])) {
            return null;
        }

        if ($locale === 'cs') {
            return $row['technique'][0];
        }

        return str($row['technique'][0])
            ->explode('; ')
            ->map(function (string $technique) use ($locale) {
                if ($locale === 'cs') {
                    return $technique;
                }

                $key = $this->techniqueTranslationKeys[$technique] ?? null;
                return $key ? trans("item.techniques.$key", locale: $locale) : null;
            })
            ->filter()
            ->implode('; ') ?:
            null;
    }

    public function mapMedium(array $row, string $locale): ?string
    {
        if (!isset($row['medium'][0])) {
            return null;
        }

        return str($row['medium'][0])
            ->explode('; ')
            ->map(fn(string $medium) => $this->mediumCorrectionMap[$medium] ?? $medium)
            ->map(function (string $medium) use ($locale) {
                if ($locale === 'cs') {
                    return $medium;
                }

                $key = $this->mediumTranslationKeys[$medium] ?? null;
                return $key ? trans("item.media.$key", locale: $locale) : null;
            })
            ->filter()
            ->implode('; ') ?:
            null;
    }
}