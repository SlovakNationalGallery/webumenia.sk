<?php

namespace App\Importers;

use App\Repositories\IFileRepository;
use Illuminate\Contracts\Translation\Translator;
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

    public function __construct(IFileRepository $repository, Translator $translator)
    {
        parent::__construct($repository, $translator);
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
            (int)Str::after($record['Inventární '], $record['Řada'])
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
        $replacements = [
            '=' => ' ',
            'cm' => ' cm',
        ];

        foreach ([
            'a' => 'primary_height',
            'a.' => 'primary_height',
            'b' => 'secondary_height',
            'b.' => 'secondary_height',
            'čas' => 'time',
            'd' => 'length',
            'd.' => 'length',
            'h' => 'depth',
            'h.' => 'depth',
            'hmot' => 'weight',
            'hmot.' => 'weight',
            'hr' => 'depth_with_frame',
            'jiný' => 'other',
            'p' => 'diameter',
            'p.' => 'diameter',
            'r.' => 'caliber',
            'ryz' => 'purity',
            's' => 'width',
            's.' => 'width',
            'sd.' => 'width_graphics_board',
            'sp' => 'width_with_mat',
            'sp.' => 'width_with_mat',
            'sr' => 'width_with_frame',
            'v' => 'overall_height_length',
            'v.' => 'overall_height_length',
            'vd.' => 'height_graphics_board',
            'vp' => 'height_with_mat',
            'vp.' => 'height_with_mat',
            'vr' => 'height_with_frame',
        ] as $key => $translationKey) {
            $replacements[$key] = $this->translator->get("item.importer.measurement.$translationKey", [], $locale);
        }

        if (empty($record['Rozměr'])) {
            return null;
        }

        return strtr($record['Rozměr'], $replacements);
    }
}
