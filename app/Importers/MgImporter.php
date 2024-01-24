<?php

namespace App\Importers;

use App\Item;

class MgImporter extends AbstractImporter
{
    use MgImporterTrait;

    protected static $options = [
        'delimiter' => ';',
        'enclosure' => '"',
        'escape' => '\\',
        'newline' => "\r\n",
        'input_encoding' => 'CP1250',
    ];

    protected $defaults = [
        'author' => 'neurčený autor', // todo translatable author
        'gallery:sk' => 'Moravská galerie, MG',
        'gallery:cs' => 'Moravská galerie, MG',
    ];

    protected $mapping = [
        'date_earliest' => 'RokOd',
        'date_latest' => 'Do',
        'acquisition_date' => 'RokAkv',
        'dating:sk' => 'Datace',
        'dating:cs' => 'Datace',
        'place:sk' => 'MístoVz',
        'place:cs' => 'MístoVz',
        'inscription:sk' => 'Sign',
        'inscription:cs' => 'Sign',
        'state_edition:sk' => 'Původnost',
        'state_edition:cs' => 'Původnost',
        'location' => 'AktLokace',
    ];

    protected array $workTypes = [
        'Ar' => 'architektura',
        'Bi' => 'bibliofilie a staré tisky',
        'Dř' => 'dřevo, nábytek a design',
        'Fo' => 'fotografie',
        'Gr' => 'grafika',
        'Gu' => 'grafický design',
        'Ji' => 'jiné',
        'Ke' => 'keramika',
        'Ko' => 'kovy',
        'Kr' => 'kresba',
        'Ob' => 'obrazy',
        'Sk' => 'sklo',
        'So' => 'sochy',
        'Šp' => 'šperky',
        'Te' => 'textil',
    ];

    protected function hydrateTitle(array $record, string $locale): ?string
    {
        if ($record['Titul'] !== null) {
            return in_array($locale, ['sk', 'cs']) ? $record['Titul'] : null;
        }
        if ($record['Předmět'] !== null) {
            return in_array($locale, ['sk', 'cs']) ? $record['Předmět'] : null;
        }
        return trans('item.untitled', locale: $locale);
    }

    protected function hydrateMedium(array $record, string $locale): ?string
    {
        $medium = $record['Materiál'];
        if ($record['MatSpec'] !== null) {
            $medium .= Item::TREE_DELIMITER . $record['MatSpec'];
        }

        if ($locale === 'cs') {
            return $medium;
        }

        $key = $this->mediumTranslationKeys[$medium] ?? null;
        return $key ? trans("item.media.$key", locale: $locale) : null;
    }

    protected function hydrateTechnique(array $record, string $locale): ?string
    {
        $technique = $record['Technika'];
        if ($record['TechSpec'] !== null) {
            $technique .= Item::TREE_DELIMITER . $record['TechSpec'];
        }

        if ($locale === 'cs') {
            return $technique;
        }

        $key = $this->techniqueTranslationKeys[$technique] ?? null;
        return $key ? trans("item.techniques.$key", locale: $locale) : null;
    }

    protected function hydrateWorkType(array $record, string $locale): ?string
    {
        $workType = $this->workTypes[$record['Skupina']] ?? null;
        if ($workType === null) {
            return null;
        }

        if ($record['Podskup'] !== null) {
            $workType .= Item::TREE_DELIMITER . $record['Podskup'];
        }

        if ($locale === 'cs') {
            return $workType;
        }

        $key = $this->workTypeTranslationKeys[$workType] ?? null;
        return $key ? trans("item.work_types.$key", locale: $locale) : null;
    }

    protected function hydrateTopic(array $record, string $locale): ?string
    {
        $topic = $record['Námět'];

        if ($locale === 'cs') {
            return $topic;
        }

        $key = $this->topicTranslationKeys[$topic] ?? null;
        return $key ? trans("item.topics.$key", locale: $locale) : null;
    }

    protected function hydrateRelationshipType(array $record, string $locale): ?string
    {
        if ($this->isBiennial($record) || $record['Rada_S'] === 'JV') {
            return trans('importer.mg.relationship_type.from_set', locale: $locale);
        }

        return null;
    }

    protected function hydrateRelatedWork(array $record, string $locale): ?string
    {
        if ($this->isBiennial($record)) {
            return trans('importer.mg.related_work.biennal_brno', locale: $locale);
        }

        if ($record['Rada_S'] === 'JV') {
            return trans('importer.mg.related_work.jv', locale: $locale);
        }

        return null;
    }

    protected function hydrateMeasurement(array $record, string $locale): ?string
    {
        if ($record['Služ'] === '=') {
            return null;
        }

        $replacements = trans('importer.demus.measurement_replacements', locale: $locale);
        return str($record['Služ'])
            ->swap($replacements)
            ->when($locale === 'en', fn($value) => $value->replace(',', '.'));
    }

    protected function isBiennial(array $record): bool
    {
        return str($record['Okolnosti'])->startsWith('BB');
    }
}
