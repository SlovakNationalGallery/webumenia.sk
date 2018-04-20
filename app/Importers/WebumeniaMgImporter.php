<?php


namespace App\Importers;


use App\Import;
use App\Repositories\IFileRepository;

class WebumeniaMgImporter extends MgImporter
{
    /** @var Import */
    protected $import;

    protected $defaults = [
        'gallery' => 'Moravská galerie, MG',
        'author' => 'neurčený autor',
        'title' => 'bez názvu',
        'topic' => 'téma',
        'relationship_type' => 'typ vzťahu',
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

    public function __construct(IFileRepository $repository) {
        parent::__construct($repository);

        $this->filters[] = function (array $record) {
            return $this->getItemIipImageUrl(null, $this->getItemImageFilename($record)) !== false;
        };

        unset($this->mapping['RokAkv'], $this->mapping['DatExp']);
    }

    public function import(Import $import, array $file) {
        $this->import = $import;
        return parent::import($import, $file);
    }

    protected function getItemIipImageUrl($csv_filename, $image_filename) {
        $paths = glob(sprintf(
            '%s/%s.jp2',
            $this->import->iip_dir_path,
            $image_filename
        ));

        return reset($paths);
    }
}