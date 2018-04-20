<?php


namespace App\Importers;


use App\Import;
use App\Repositories\IFileRepository;

class WebumeniaMgImporter extends MgImporter
{
    /** @var Import */
    protected $import;

    /** @var array */
    protected $file;

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
            return file_exists($this->getItemIipImagePath($this->file['basename'], $this->getItemImageFilename($record)));
        };

        unset($this->mapping['RokAkv'], $this->mapping['DatExp']);
    }

    public function import(Import $import, array $file) {
        $this->import = $import;
        $this->file = $file;
        return parent::import($import, $file);
    }

    /**
     * @param string $csv_filename
     * @param string $image_filename
     * @return string
     */
    protected function getItemIipImagePath($csv_filename, $image_filename) {
        return sprintf(
            '%s/%s',
            $this->import->iip_dir_path,
            $this->getItemIipImageUrl($csv_filename, $image_filename)
        );
    }
}