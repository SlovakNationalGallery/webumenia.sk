<?php

namespace App\Importers;

use App\Medium;
use App\Technique;
use App\Topic;
use App\WorkType;

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

    protected function init(): void
    {
        $this->filters[] = function (array $record) {
            return $record['Plus2T'] != 'ODPIS';
        };

        $this->sanitizers[] = function ($value) {
            return empty_to_null($value);
        };
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
        if (!in_array($locale, ['cs', 'sk'])) {
            return null;
        }

        if ($record['Titul'] !== null) {
            return $record['Titul'];
        } elseif ($record['Předmět'] !== null) {
            return $record['Předmět'];
        } else {
            return null;
        }
    }

    protected function hydrateMedium(array $record, string $locale): ?string
    {
        $medium = Medium::query()
            ->whereTranslation('name', $record['Materiál'], 'cs')
            ->whereNull('parent_id')
            ->firstOr(fn() => Medium::create(['name:cs' => $record['Materiál']]));

        if ($record['MatSpec'] !== null) {
            $medium = $medium
                ->children()
                ->whereTranslation('name', $record['MatSpec'], 'cs')
                ->firstOr(
                    fn() => Medium::create([
                        'name:cs' => $record['MatSpec'],
                        'parent_id' => $medium->id,
                    ])
                );
        }

        return $medium->getFullName($locale);
    }

    protected function hydrateTechnique(array $record, string $locale): ?string
    {
        $technique = Technique::query()
            ->whereTranslation('name', $record['Technika'], 'cs')
            ->firstOr(fn() => Technique::create(['name:cs' => $record['Technika']]));

        if ($record['TechSpec'] !== null) {
            $technique = $technique
                ->children()
                ->whereTranslation('name', $record['TechSpec'], 'cs')
                ->firstOr(
                    fn() => Technique::create([
                        'name:cs' => $record['TechSpec'],
                        'parent_id' => $technique->id,
                    ])
                );
        }

        return $technique->getFullName($locale);
    }

    protected function hydrateWorkType(array $record, string $locale): ?string
    {
        $workType = $this->workTypes[$record['Skupina']] ?? null;
        if ($workType === null) {
            return null;
        }

        $workType = WorkType::query()
            ->whereTranslation('name', $workType, 'cs')
            ->firstOr(fn() => WorkType::create(['name:cs' => $workType]));

        if ($record['Podskup'] !== null) {
            $workType = $workType
                ->children()
                ->whereTranslation('name', $record['Podskup'], 'cs')
                ->firstOr(
                    fn() => WorkType::create([
                        'name:cs' => $record['Podskup'],
                        'parent_id' => $workType->id,
                    ])
                );
        }

        return $workType->getFullName($locale);
    }

    protected function hydrateTopic(array $record, string $locale): ?string
    {
        if ($record['Námět'] === null) {
            return null;
        }

        return Topic::query()
            ->whereTranslation('name', $record['Námět'], 'cs')
            ->firstOr(fn() => Topic::create(['name:cs' => $record['Námět']]))
            ->translate($locale)?->name;
    }

    protected function hydrateRelationshipType(array $record, string $locale): ?string
    {
        if ($this->isBiennial($record) || $record['Rada_S'] === 'JV') {
            return $this->translator->get(
                'importer.mg.relationship_type.from_set',
                locale: $locale
            );
        }

        return null;
    }

    protected function hydrateRelatedWork(array $record, string $locale): ?string
    {
        if ($this->isBiennial($record)) {
            return $this->translator->get('importer.mg.related_work.biennal_brno', locale: $locale);
        }

        if ($record['Rada_S'] === 'JV') {
            return $this->translator->get('importer.mg.related_work.jv', locale: $locale);
        }

        return null;
    }

    protected function hydrateMeasurement(array $record, string $locale): ?string
    {
        if ($record['Služ'] === '=') {
            return null;
        }

        $replacements = $this->translator->get(
            'importer.demus.measurement_replacements',
            locale: $locale
        );
        return str($record['Služ'])
            ->swap($replacements)
            ->when($locale === 'en', fn($value) => $value->replace(',', '.'));
    }

    protected function isBiennial(array $record): bool
    {
        return str($record['Okolnosti'])->startsWith('BB');
    }
}
