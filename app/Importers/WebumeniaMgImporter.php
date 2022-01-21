<?php


namespace App\Importers;


use App\Import;
use App\Repositories\IFileRepository;
use Illuminate\Contracts\Translation\Translator;

class WebumeniaMgImporter extends MgImporter
{
    /** @var Import */
    protected $import;

    /** @var array */
    protected $csv_file;

    protected $mapping = [
        'acquisition_date' => 'RokAkv',
        'copyright_expires' => 'DatExp',
        'dating:sk' => 'Datace',
        'date_earliest' => 'RokOd',
        'date_latest' => 'Do',
        'place:sk' => 'MístoVz',
        'inscription:sk' => 'Sign',
        'state_edition:sk' => 'Původnost',
        'author' => 'Autor',
        'title:sk' => 'Titul',
        'topic:sk' => 'Námět',
    ];

    protected $defaults = [
        'author' => 'Neznámy autor',
        'gallery:sk' => 'Moravská galerie, MG',
        'title:sk' => 'bez názvu',
        'topic:sk' => 'téma',
        'relationship_type:sk' => 'typ vzťahu',
        'description:sk' => '',
        'work_level:sk' => '',
        'subject:sk' => '',
    ];

    protected static $cz_work_types_spec = [
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

    protected static $cz_measurement_replacements = [
        'a' => 'výška hlavnej časti',
        'a.' => 'výška hlavnej časti',
        'b' => 'výška vedľajšej časti',
        'b.' => 'výška vedľajšej časti',
        'čas' => 'čas',
        'd' => 'dĺžka',
        'd.' => 'dĺžka',
        'h' => 'hĺbka/hrúbka',
        'h.' => 'hĺbka/hrúbka',
        'hmot' => 'hmotnosť',
        'hmot.' => 'hmotnosť',
        'hr' => 'hĺbka s rámom',
        'jiný' => 'iný nešpecifikovaný',
        'p' => 'priemer/ráž',
        'p.' => 'priemer/ráž',
        'r.' => 'ráž',
        'ryz' => 'rýdzosť',
        's' => 'šírka',
        's.' => 'šírka',
        'sd.' => 'šírka grafickej dosky',
        'sp' => 'šírka s paspartou',
        'sp.' => 'šírka s paspartou',
        'sr' => 'šírka s rámom',
        'v' => 'celková výška/dĺžka',
        'v.' => 'celková výška/dĺžka',
        'vd.' => 'výška grafickej dosky',
        'vp' => 'výška s paspartou',
        'vp.' => 'výška s paspartou',
        'vr' => 'výška s rámom',
        ';' => ';',
        '=' => ' ',
        'cm' => ' cm',
    ];

    protected static $cz_technique_replacements = [
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

    public function __construct(IFileRepository $repository, Translator $translator) {
        parent::__construct($repository, $translator);

        $this->filters['with_iip'] = function (array $record) {
            $image_filename_format = $this->getItemImageFilenameFormat($record);
            return !empty($this->getImageJp2Paths($this->import, $this->csv_file['basename'], $image_filename_format));
        };

        unset($this->mapping['RokAkv'], $this->mapping['DatExp']);
    }

    public function import(Import $import, array $file) {
        $this->import = $import;
        $this->csv_file = $file;
        return parent::import($import, $file);
    }

    protected function hydrateTechnique(array $record) {
        $technique = parent::hydrateTechnique($record);
        return strtr($technique, static::$cz_technique_replacements);
    }

    protected function hydrateWorkType(array $record) {
        return (isset(static::$cz_work_types_spec[$record['Skupina']])) ? static::$cz_work_types_spec[$record['Skupina']] : '';
    }

    protected function hydrateRelationshipType(array $record) {
        return self::isBiennial($record) ? 'zo súboru' : '';
    }

}