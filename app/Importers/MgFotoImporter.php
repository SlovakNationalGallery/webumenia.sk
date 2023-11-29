<?php

namespace App\Importers;

use App\Item;

class MgFotoImporter extends AbstractMgImporter
{
    protected $mapping = [
        'date_earliest' => 'RokVzOd',
        'date_latest' => 'RokVzDo',
        'acquisition_date' => 'RokAkv',
        'author' => 'Autor',
        'dating:sk' => 'DatVz',
        'dating:cs' => 'DatVz',
        //        'location:sk' => 'AktLokace', todo
        //        'location:cs' => 'AktLokace', todo
        'description:sk' => 'Popis',
        'description:cs' => 'Popis',
    ];

    protected array $surfaces = [
        'J' => 'jiný',
        'L' => 'lesklý',
        'M' => 'matný',
        'P' => 'polomatný',
        'R' => 'rastr',
        'V' => 'velvet',
    ];

    protected array $zooms = [
        'K' => 'kontakt fotografie',
        'M' => 'zmenšenina fotografie',
        'V' => 'zväčšenina fotografie',
    ];

    protected array $colors = [
        'B' => 'farebná fotografia',
        'C' => 'čiernobiela fotografia',
        'CK' => 'kolorováno fotografie',
        'CP' => 'pastelový papier fotografia',
        'CT' => 'tónovaná fotografia',
        'CTK' => 'tónovaná, kolorovaná fotografia',
        'J' => 'iná fotografia',
    ];

    protected array $stateEditions = [
        'K' => 'kópia',
        'N' => 'neznámy',
        'O' => 'originál',
        'P' => 'reprodukcia',
    ];

    protected function hydrateTitle(array $record, string $locale): ?string
    {
        if ($record['Název'] !== null) {
            return in_array($locale, ['sk', 'cs']) ? $record['Název'] : null;
        } elseif ($record['Předmět'] !== null) {
            return in_array($locale, ['sk', 'cs']) ? $record['Předmět'] : null;
        } else {
            return $this->translator->get('item.untitled', locale: $locale);
        }
    }

    protected function hydrateStateEdition(array $record, string $locale): ?string
    {
        if ($record['Původnost'] === null) {
            return null;
        }

        $key = $this->stateEditions[$record['Původnost']];
        return trans("item.state_editions.$key", locale: $locale);
    }

    protected function hydrateMedium(array $record, string $locale): ?string
    {
        $medium = $record['Materiál'];
        if ($record['Povrch'] !== null) {
            $medium .= Item::TREE_DELIMITER . $this->surfaces[$record['Povrch']];
        }

        if ($locale === 'cs') {
            return $medium;
        }

        $key = $this->mediumTranslationKeys[$medium] ?? null;
        return $key ? trans("item.media.$key", locale: $locale) : null;
    }

    protected function hydrateTechnique(array $record, string $locale): ?string
    {
        $techniques = collect();
        if ($record['Zoom'] !== null) {
            $key = $this->zooms[$record['Zoom']];
            $techniques[] = trans("item.techniques.$key", locale: $locale);
        }

        if ($record['Barva'] !== null) {
            $key = $this->colors[$record['Barva']];
            $techniques[] = trans("item.techniques.$key", locale: $locale);
        }

        return $techniques->join('; ') ?: null;
    }

    protected function hydrateWorkType(array $record, string $locale): ?string
    {
        $workType = 'fotografie';
        if ($record['Předmět'] !== null) {
            $workType .= Item::TREE_DELIMITER . $record['Předmět'];
        }

        if ($locale === 'cs') {
            return $workType;
        }

        $key = $this->workTypeTranslationKeys[$workType] ?? null;
        return $key ? trans("item.work_types.$key", locale: $locale) : null;
    }

    protected function hydrateRelationshipType(array $record, string $locale): ?string
    {
        if ($record['Okolnosti'] === 'Archiv negativů Dagmar Hochové') {
            return trans('importer.mg.relationship_type.from_set', locale: $locale);
        }

        return null;
    }

    protected function hydrateRelatedWork(array $record, string $locale): ?string
    {
        if ($record['Okolnosti'] === 'Archiv negativů Dagmar Hochové') {
            return trans('importer.mg.related_work.dagmar_hochova', locale: $locale);
        }

        return null;
    }
}
