<?php

namespace App\Importers;

use Illuminate\Support\Str;

class GmuRnlImporter extends AbstractImporter
{
    protected $mapping = [
        'title:sk' => 'Titul',
        'dating:sk' => 'Datace',
        'date_earliest' => 'Od',
        'date_latest' => 'Do',
        'medium:sk' => 'Materiál',
        'technique:sk' => 'Technika',
        'measurement:sk' => 'Rozměr',
        'topic:sk' => 'Námět/téma',
        'inscription:sk' => 'Signatura (aversu)',
        'acquisition_date' => 'Datum nabytí',
        'author' => 'Autor',
        'identifier' => 'Inventární ',
    ];

    protected $defaults = [
        'gallery:sk' => 'Galerie moderního umění v Roudnici nad Labem',
    ];

    protected $options = [
        'delimiter' => ';',
        'enclosure' => '"',
        'escape' => '\\',
        'newline' => "\n",
        'input_encoding' => 'CP1250',
    ];

    protected static $name = 'gml-rnl';

    protected function init()
    {
        $this->sanitizers[] = function ($value) {
            return empty_to_null($value);
        };
    }

    protected function getItemId(array $record)
    {
        $id = $record['Inventární '];
        $id = preg_replace('/[^\w]+/', '_', $id);
        return sprintf('CZE:4RG.%s', $id);
    }

    protected function getItemImageFilenameFormat(array $record)
    {
        return sprintf(
            '%s_%d{_*,}',
            $record['Řada'],
            (int) Str::after($record['Inventární '], $record['Řada'])
        );
    }

    protected function hydrateWorkType(array $record, $locale)
    {
        $workType = [
            'G' => 'graphics',
            'K' => 'drawing',
            'O' => 'image',
            'S' => 'sculpture',
            'U' => 'applied_arts',
            'F' => 'photography',
        ][$record['Řada']];

        return $this->translator->get("item.importer.work_type.$workType", [], $locale);
    }

    protected function hydrateMeasurement(array $record, $locale)
    {
        if (empty($record['Rozměr'])) {
            return null;
        }

        $replacements = $this->translator->get('item.measurement_replacements', [], $locale);
        return strtr($record['Rozměr'], $replacements);
    }
}
