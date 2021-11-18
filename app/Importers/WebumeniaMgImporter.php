<?php

namespace App\Importers;

use App\Import;
use App\Repositories\IFileRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Arr;

class WebumeniaMgImporter extends MgImporter
{
    /** @var Import */
    protected $import;

    /** @var array */
    protected $csv_file;

    protected static $sk_work_types_spec = [
        'Ar' => 'architektúra',
        'Bi' => 'bibliofília a staré tlače',
        'Dř' => 'drevo, nábytok a dizajn',
        'Fo' => 'fotografia',
        'Gr' => 'grafika',
        'Gu' => 'grafický dizajn',
        'Ji' => 'iné',
        'Ke' => 'keramika',
        'Ko' => 'kov',
        'Kr' => 'kresba',
        'Ob' => 'maliarstvo',
        'Sk' => 'sklo',
        'So' => 'sochárstvo',
        'Šp' => 'šperk',
        'Te' => 'textil',
    ];

    protected static $sk_measurement_replacements = [
        'výška hlavní části' => 'výška hlavnej časti',
        'výška vedlejší části' => 'výška vedľajšej časti',
        'čas' => 'čas',
        'délka' => 'dĺžka',
        'hloubka/tloušťka' => 'hĺbka/hrúbka',
        'hmotnost' => 'hmotnosť',
        'hloubka s rámem' => 'hĺbka s rámom',
        'jiný nespecifikovaný' => 'iný nešpecifikovaný',
        'průměr/ráže' => 'priemer/ráž',
        'ráže' => 'ráž',
        'ryzost' => 'rýdzosť',
        'šířka' => 'šírka',
        'šířka grafické desky' => 'šírka grafickej dosky',
        'šířka s paspartou' => 'šírka s paspartou',
        'šířka s rámem' => 'šírka s rámom',
        'celková výška/délka' => 'celková výška/dĺžka',
        'výška grafické desky' => 'výška grafickej dosky',
        'výška s paspartou' => 'výška s paspartou',
        'výška s rámem' => 'výška s rámom',
    ];

    protected static $sk_technique_replacements = [
        'tužka' => 'ceruza',
        'zlacení' => 'zlátenie',
        'litografie' => 'litografia',
        'řezání' => 'rezanie',
        'lití' => 'liatie',
        'slepotisk' => 'slepotlač',
        'skulptura' => '',
        'vyřezávání' => 'vyrezávanie',
        'rytí' => 'rytie',
        'tepání' => 'tepanie',
        'křída' => 'krieda',
        'mědiryt' => 'medirytina',
        'kresba perem, lavírování' => 'pero, lavírovanie',
    ];

    protected static $sk_relationship_type_replacements = [
        'ze souboru' => 'zo súboru',
    ];

    protected $locationMap = [
        'UPM' => [
            '211' => [
                'B01' => 'Jaroslav Brychta a jeho vliv',
            ]
        ]
    ];

    public function __construct(IFileRepository $repository, Translator $translator) {
        parent::__construct($repository, $translator);

        // $this->filters['with_iip'] = function (array $record) {
        //     $image_filename_format = $this->getItemImageFilenameFormat($record);
        //     return !empty($this->getImageJp2Paths($this->import, $this->csv_file['basename'], $image_filename_format));
        // };

        unset(
            $this->mapping['acquisition_date'],
            $this->mapping['copyright_expires']
        );

        $this->mapping += [
            'dating:sk' => 'Datace',
            'inscription:sk' => 'Sign',
            'place:sk' => 'MístoVz',
            'title:sk' => 'Titul',
            'topic:sk' => 'Námět',
            'state_edition:sk' => 'Původnost',
        ];

        $this->defaults += [
            'gallery:sk' => 'Moravská galerie, MG',
            'title:sk' => 'bez názvu',
            'topic:sk' => 'téma',
            'relationship_type:sk' => 'typ vzťahu',
            'description:sk' => '',
            'work_level:sk' => '',
            'subject:sk' => '',
        ];
    }

    public function import(Import $import, array $file) {
        $this->import = $import;
        $this->csv_file = $file;
        return parent::import($import, $file);
    }

    protected function hydrateTitle(array $record, $locale = null) {
        if ($locale === 'cs' || $locale === 'sk') {
            return parent::hydrateTitle($record);
        }
    }

    protected function hydrateMedium(array $record, $locale = null) {
        if ($locale === 'cs' || $locale === 'sk') {
            return parent::hydrateMedium($record);
        }
    }

    protected function hydrateTechnique(array $record, $locale = null) {
        $technique = parent::hydrateTechnique($record);
        if ($locale === 'cs') {
            return $technique;
        } else if ($locale === 'sk') {
            return strtr($technique, static::$sk_technique_replacements);
        }
    }

    protected function hydrateWorkType(array $record, $locale = null) {
        if ($locale === 'cs') {
            return parent::hydrateWorkType($record);
        } else if ($locale === 'sk') {
            return isset(static::$sk_work_types_spec[$record['Skupina']]) ? static::$sk_work_types_spec[$record['Skupina']] : '';
        }
    }

    protected function hydrateRelationshipType(array $record, $locale = null) {
        $relationshipType = parent::hydrateRelationshipType($record);
        if ($locale === 'cs') {
            return $relationshipType;
        } else if ($locale === 'sk') {
            return strtr($relationshipType, static::$sk_relationship_type_replacements);
        }
    }

    protected function hydrateRelatedWork(array $record, $locale = null) {
        $relatedWork = parent::hydrateRelatedWork($record);
        if ($locale === 'cs' || $locale === 'sk') {
            return $relatedWork;
        }
    }

    protected function hydrateMeasurement(array $record, $locale = null) {
        $measurement = parent::hydrateMeasurement($record);
        if ($locale === 'cs') {
            return $measurement;
        } else if ($locale === 'sk') {
            return strtr($measurement, static::$sk_measurement_replacements);
        }
    }

    protected function hydrateAdditionals(array $record) {
        $additionals = [
            'frontend' => ['sbirky.moravska-galerie.cz']
        ];

        $locations = $this->getLocations($record['AktLokace'] ?? null);
        if ($locations) {
            $additionals['location'] = $locations;
        }

        return $additionals;
    }

    protected function getLocations($location)
    {
        $exploded = explode('/', $location);
        $locations = [];

        if (count($exploded) >= 2 && $exploded[0] === 'UPM' && $exploded[1] === '211') {
            $locations[] = 'LIGHT DEPO / Otevřený depozitář skla';

            if (count($exploded) >= 3 && preg_match('/^B(?<number>\d+)$/', $exploded[2], $matches)) {
                $boxLocation = sprintf('LIGHT DEPO / BOX %s', $matches['number']);

                $key = implode('.', $exploded);
                $title = Arr::get($this->locationMap, $key);

                if ($title !== null) {
                    $boxLocation = sprintf('%s – %s', $boxLocation, $title);
                }

                $locations[] = $boxLocation;
            }
        }

        if ($locations) {
            $locations[] = $location;
        }

        return $locations;
    }
}
