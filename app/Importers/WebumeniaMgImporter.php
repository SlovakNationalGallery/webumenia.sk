<?php


namespace App\Importers;


use App\Import;
use App\Repositories\IFileRepository;

class WebumeniaMgImporter extends MgImporter
{
    /** @var Import */
    protected $import;

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