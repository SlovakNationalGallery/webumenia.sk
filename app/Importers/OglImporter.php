<?php

namespace App\Importers;

use App\Repositories\IFileRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Str;

class OglImporter extends AbstractImporter
{
    protected $options = [
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
    ];

    protected $defaults = [
        'gallery:sk' => 'Oblastní galerie Liberec, OGL',
        'gallery:cs' => 'Oblastní galerie Liberec, OGL',
    ];

    public static $name = 'ogl';

    public function __construct(IFileRepository $repository, Translator $translator) {
        parent::__construct($repository, $translator);
        $this->sanitizers[] = function ($value) {
            return empty_to_null($value);
        };
    }

    protected function getItemId(array $record) {
        $id = sprintf('CZE:OGL.%s_%s', $record['Rada_S'], (int)$record['PorC_S']);
        if ($record['Lomeni_S'] != '_') {
            $id = sprintf('%s-%s', $id, $record['Lomeni_S']);
        }

        return $id;
    }

    protected function getItemImageFilenameFormat(array $record) {
        $filename = sprintf('%s%s', $record['Rada_S'], str_pad($record['PorC_S'], 5, '0', STR_PAD_LEFT));
        if ($record['Lomeni_S'] != '_') {
            $filename = sprintf('%s_%s', $filename, $record['Lomeni_S']);
        }

        return $filename . '{_*,}';
    }

    protected function hydrateWorkType(array $record, $locale) {
        return $this->translateAttribute("work_type", $record['Skupina'], $locale);
    }

    protected function hydrateTopic(array $record, $locale) {
        $topic = Str::lower($record['Námět']);
        if ($locale !== 'cs') {
            $topic = $this->translateAttribute("topic", $topic, $locale);
        }

        return $topic;
    }

    protected function hydrateTechnique(array $record, $locale) {
        $technika = $record['Technika'];
        if ($locale !== 'cs') {
            $technika = $this->translateAttribute("technique", $record['Technika'], $locale);
        }

        if (!$record['TechSpec']) {
            return $technika;
        }

        $techSpec = $record['TechSpec'];
        if ($locale !== 'cs') {
            $techSpec = $this->translateAttribute("technique", $techSpec, $locale);
        }

        if (!$technika || !$techSpec) {
            return null;
        }

        return "$technika, $techSpec";
    }

    protected function hydrateMeasurement(array $record, $locale) {
        if (empty($record['SlužF']) || $record['SlužF'] === '=') {
            return null;
        }

        $replacements = $this->translator->get('importer.demus.measurement_replacements', [], $locale);
        return strtr($record['SlužF'], $replacements);
    }

    protected function translateAttribute($attribute, $key, $locale)
    {
        if (!$this->translator->hasForLocale("importer.demus.$attribute.$key", $locale)) {
            return null;
        }

        return $this->translator->get("importer.demus.$attribute.$key", [], $locale);
    }
}