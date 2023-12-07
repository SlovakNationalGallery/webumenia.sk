<?php

namespace App\Importers;

use App\Item;

class MgImporter extends AbstractImporter
{
    protected static $options = [
        'delimiter' => ';',
        'enclosure' => '"',
        'escape' => '\\',
        'newline' => "\r\n",
        'input_encoding' => 'CP1250',
    ];

    protected $mapping = [
        'date_earliest' => 'RokOd',
        'date_latest' => 'Do',
        'acquisition_date' => 'RokAkv',
        'author' => 'Autor',
        'dating:sk' => 'Datace',
        'dating:cs' => 'Datace',
        'place:sk' => 'MístoVz',
        'place:cs' => 'MístoVz',
        'inscription:sk' => 'Sign',
        'inscription:cs' => 'Sign',
        'state_edition:sk' => 'Původnost',
        'state_edition:cs' => 'Původnost',
    ];

    protected $defaults = [
        'author' => 'neurčený autor', // todo translatable author
        'gallery:sk' => 'Moravská galerie, MG',
        'gallery:cs' => 'Moravská galerie, MG',
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

    protected array $mediumTranslationKeys;
    protected array $techniqueTranslationKeys;
    protected array $workTypeTranslationKeys;
    protected array $topicTranslationKeys;

    protected function init(): void
    {
        $this->filters[] = function (array $record) {
            return $record['Plus2T'] != 'ODPIS';
        };

        $this->sanitizers[] = function ($value) {
            return empty_to_null($value);
        };

        $this->mediumTranslationKeys = array_flip(trans('item.media', locale: 'cs'));
        $this->techniqueTranslationKeys = array_flip(trans('item.techniques', locale: 'cs'));
        $this->workTypeTranslationKeys = array_flip(trans('item.work_types', locale: 'cs'));
        $this->topicTranslationKeys = array_flip(trans('item.topics', locale: 'cs'));
    }

    protected function getItemId(array $record): string
    {
        $id = sprintf('CZE:MG.%s_%s', $record['Rada_S'], (int) $record['PorC_S']);
        if ($record['Lomeni_S'] != '_') {
            $id = sprintf('%s-%s', $id, $record['Lomeni_S']);
        }

        return $id;
    }

    protected function getItemImageFilenameFormat(array $record): string
    {
        $filename = sprintf(
            '%s%s',
            $record['Rada_S'],
            str_pad($record['PorC_S'], 6, '0', STR_PAD_LEFT)
        );
        if ($record['Lomeni_S'] != '_') {
            $filename = sprintf('%s-%s', $filename, $record['Lomeni_S']);
        }

        return sprintf('%s(_.*)?', preg_quote($filename));
    }

    protected function hydrateIdentifier(array $record): string
    {
        $identifier = sprintf('%s %s', $record['Rada_S'], (int) $record['PorC_S']);
        if ($record['Lomeni_S'] != '_') {
            $identifier = sprintf('%s/%s', $identifier, $record['Lomeni_S']);
        }

        return $identifier;
    }

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
