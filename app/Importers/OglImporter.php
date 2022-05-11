<?php

namespace App\Importers;

use Illuminate\Support\Str;

class OglImporter extends AbstractImporter
{
    protected static $options = [
        'delimiter' => ';',
        'enclosure' => '"',
        'escape' => '\\',
        'newline' => "\r\n",
    ];

    protected $mapping = [
        'author' => 'Autor',
        'date_earliest' => 'RokOd',
        'date_latest' => 'Do',
        'dating:cs' => 'Datace',
        'dating:sk' => 'Datace',
        'place:cs' => 'MístoVz',
        'place:sk' => 'MístoVz',
        'inscription:cs' => 'Sign',
        'inscription:sk' => 'Sign',
        'state_edition:cs' => 'Původnost',
        'state_edition:sk' => 'Původnost',
        'title:cs' => 'Titul',
        'title:sk' => 'Titul',
        'current_location:cs' => 'AktLokace',
        'current_location:sk' => 'AktLokace',
    ];

    protected $defaults = [
        'gallery:sk' => 'Oblastní galerie Liberec, OGL',
        'gallery:cs' => 'Oblastní galerie Liberec, OGL',
    ];

    public static $name = 'ogl';

    protected function init()
    {
        $this->sanitizers[] = function ($value) {
            return empty_to_null($value);
        };
    }

    protected function getItemId(array $record)
    {
        $id = sprintf('CZE:OGL.%s_%s', $record['Rada_S'], $record['PorC_S']);
        if ($record['Lomeni_S'] != '_') {
            $id = sprintf('%s-%s', $id, $record['Lomeni_S']);
        }

        return $id;
    }

    protected function getItemImageFilenameFormat(array $record)
    {
        $filename = sprintf(
            '%s%s',
            $record['Rada_S'],
            str_pad($record['PorC_S'], 5, '0', STR_PAD_LEFT)
        );
        if ($record['Lomeni_S'] != '_') {
            $filename = sprintf('%s%s', $filename, $record['Lomeni_S']);
        }

        return $filename . '{_*,}';
    }

    protected function hydrateIdentifier(array $record)
    {
        $identifier = sprintf('%s %s', $record['Rada_S'], $record['PorC_S']);
        if ($record['Lomeni_S'] != '_') {
            $identifier = sprintf('%s/%s', $identifier, $record['Lomeni_S']);
        }

        return $identifier;
    }

    protected function hydrateWorkType(array $record, $locale)
    {
        $workType = config('demus.work_type')[$record['Skupina']];
        if ($locale !== 'cs') {
            $workType = $this->translateAttribute('work_type', $workType, $locale);
        }

        return $workType;
    }

    protected function hydrateTopic(array $record, $locale)
    {
        $topic = Str::lower($record['Námět']);
        if ($locale !== 'cs') {
            $topic = $this->translateAttribute('topic', $topic, $locale);
        }

        return $topic;
    }

    protected function hydrateStylePeriod(array $record, $locale)
    {
        $stylePeriod = $record['Podskup'];
        if ($locale !== 'cs') {
            $stylePeriod = $this->translateAttribute('style_period', $stylePeriod, $locale);
        }

        return $stylePeriod;
    }

    protected function hydrateTechnique(array $record, $locale)
    {
        $technika = $record['Technika'];
        if ($locale !== 'cs') {
            $technika = $this->translateAttribute('technique', $technika, $locale);
        }

        if (!$record['TechSpec']) {
            return $technika;
        }

        $techSpec = $record['TechSpec'];
        if ($locale !== 'cs') {
            $techSpec = $this->translateAttribute('technique', $techSpec, $locale);
        }

        return collect([$technika, $techSpec])
            ->filter()
            ->join(', ') ?:
            null;
    }

    protected function hydrateMedium(array $record, $locale)
    {
        $material = $record['Materiál'];
        if ($locale !== 'cs') {
            $material = $this->translateAttribute('medium', $record['Materiál'], $locale);
        }

        if (!$record['MatSpec']) {
            return $material;
        }

        $matSpec = $record['MatSpec'];
        if ($locale !== 'cs') {
            $matSpec = $this->translateAttribute('medium', $record['MatSpec'], $locale);
        }

        return collect([$material, $matSpec])
            ->filter()
            ->join(', ') ?:
            null;
    }

    protected function hydrateMeasurement(array $record, $locale)
    {
        if (empty($record['SlužF']) || $record['SlužF'] === '=') {
            return null;
        }

        $replacements = $this->translator->get(
            'importer.demus.measurement_replacements',
            [],
            $locale
        );
        return strtr($record['SlužF'], $replacements);
    }

    protected function translateAttribute($attribute, $key, $locale)
    {
        if (!$this->translator->hasForLocale("importer.cs.$attribute.$key", $locale)) {
            return null;
        }

        return $this->translator->get("importer.cs.$attribute.$key", [], $locale);
    }
}
