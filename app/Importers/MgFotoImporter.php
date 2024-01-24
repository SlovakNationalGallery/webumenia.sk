<?php

namespace App\Importers;

use App\Item;
use Illuminate\Support\Arr;

class MgFotoImporter extends AbstractImporter
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
        'date_earliest' => 'RokVzOd',
        'date_latest' => 'RokVzDo',
        'acquisition_date' => 'RokAkv',
        'dating:sk' => 'DatVz',
        'dating:cs' => 'DatVz',
        'description:sk' => 'Popis',
        'description:cs' => 'Popis',
        'location' => 'AktLokace',
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
        'K' => 'kontaktná kópia',
        'M' => 'zmenšovanie',
        'V' => 'zväčšovanie',
    ];

    protected array $stateEditions = [
        'AP' => 'autorizovaný pozitív',
        'F' => 'faksimile',
        'J' => 'iný',
        'K' => 'kópia',
        'NAP' => 'neautorizovaný pozitív',
        'O' => 'originál',
        'RT' => 'tlačová reprodukcia',
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
        $media = collect();

        $medium = $record['Materiál'];
        if ($record['Povrch'] !== null) {
            $medium .= Item::TREE_DELIMITER . $this->surfaces[$record['Povrch']];
        }
        if ($medium) {
            $media[] = $medium;
        }

        $colorMedia = [
            'CP' => 'papír/pastelový papír',
        ];
        if ($record['Barva'] !== null && Arr::has($colorMedia, $record['Barva'])) {
            $media[] = Arr::get($colorMedia, $record['Barva']);
        }

        return $media
            ->map(function (string $medium) use ($locale) {
                if ($locale === 'cs') {
                    return $medium;
                }

                $key = $this->mediumTranslationKeys[$medium] ?? null;
                return $key ? trans("item.media.$key", locale: $locale) : null;
            })
            ->filter()
            ->join('; ');
    }

    protected function hydrateTechnique(array $record, string $locale): ?string
    {
        $techniqueTranslationKeys = collect();
        if ($record['Zoom'] !== null) {
            $techniqueTranslationKeys[] = $this->zooms[$record['Zoom']];
        }

        $colors = [
            'B' => ['farebná fotografia'],
            'C' => ['čiernobiela fotografia'],
            'CK' => ['kolorovanie'],
            'CT' => ['tónovanie'],
            'CTK' => ['tónovanie', 'kolorovanie'],
            'J' => ['iná technika'],
        ];
        $colorTranslationKeys = $colors[$record['Barva']] ?? null;
        if ($colorTranslationKeys) {
            foreach ($colorTranslationKeys as $colorTranslationKey) {
                $techniqueTranslationKeys[] = $colorTranslationKey;
            }
        }

        return $techniqueTranslationKeys
            ->map(fn(string $key) => trans("item.techniques.$key", locale: $locale))
            ->join('; ') ?:
            null;
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
