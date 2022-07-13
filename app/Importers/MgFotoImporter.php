<?php


namespace App\Importers;


use App\Repositories\IFileRepository;

class MgFotoImporter extends MgImporter {

    protected $mapping = [
        'DatVz' => 'dating',
        'RokVzOd' => 'date_earliest',
        'RokVzDo' => 'date_latest',
        'Původnost' => 'state_edition',
        'Autor' => 'author',
        'AktLokace' => 'location',
    ];

    protected $defaults = [
        'gallery' => 'Moravská galerie, MG',
        'author' => 'neurčený autor',
        'topic' => '',
        'relationship_type' => '',
        'place' => '',
    ];

    protected static $cz_technique_spec = [
        'M' => 'zmenšenina',
        'V' => 'zvětšenina',
        'K' => 'kontakt',
    ];

    protected static $cz_color_spec = [
        'B' => 'barva',
        'C' => 'černobílá',
        'CK' => 'kolorováno',
        'CP' => 'pastelový papír',
        'CT' => 'tónováno',
        'CTK' => 'tónováno, kolorováno',
        'J' => 'jiné',
    ];

    protected static $cz_state_edition_spec = [
        'AP' => 'autorizovaný pozitiv',
        'F' => 'facsimile',
        'J' => 'jiné',
        'K' => 'kopie',
        'NAP' => 'neautorský pozitiv',
        'O' => 'originál',
        'RT' => 'reprodukce tisková',
    ];

    protected static $cz_surface_spec = [
        'J' => 'jiný',
        'L' => 'lesklý',
        'M' => 'matný',
        'P' => 'polomatný',
        'R' => 'rastr',
        'V' => 'velvet',
    ];

    protected static $name = 'mg_foto';

    public function __construct(IFileRepository $repository) {
        parent::__construct($repository);
    }

    protected function hydrateWorkType(array $record) {
        $work_type = ['fotografie'];
        if ($record['Předmět'] !== null) {
            $work_type[] = $record['Předmět'];
        }

        return implode(', ', $work_type);
    }

    protected function hydrateTechnique(array $record) {
        $key = 'Zoom';
        $technique = [];
        if (isset(self::$cz_technique_spec[$record[$key]])) {
            $technique[] = self::$cz_technique_spec[$record[$key]];
        }

        $key = 'Barva';
        if (isset(self::$cz_color_spec[$record[$key]])) {
            $technique[] = self::$cz_color_spec[$record[$key]];
        }

        return implode(', ', $technique);
    }

    protected function hydrateStateEdition(array $record) {
        $key = 'Původnost';
        if (isset(self::$cz_state_edition_spec[$record[$key]])) {
            return self::$cz_state_edition_spec[$record[$key]];
        }
    }

    protected function hydrateMedium(array $record) {
        $medium = (array)parent::hydrateMedium($record);

        $key = 'Povrch';
        if (isset(self::$cz_surface_spec[$record[$key]])) {
            $medium[] = self::$cz_surface_spec[$record[$key]];
        }

        return implode(', ', $medium);
    }

    protected function hydrateAcquisitionDate(array $record) {
        $key = 'DatAkv';
        if ($date = \DateTime::createFromFormat('j-n-y', $record[$key])) {
            return $date->format('Y');
        }
    }

    protected function hydrateTitle(array $record) {
        if ($record['Název'] !== null) {
            return $record['Název'];
        } else if ($record['Předmět'] !== null) {
            return $record['Předmět'];
        } else {
            return 'bez názvu';
        }
    }
}