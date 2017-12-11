<?php

namespace App\Importers;

use App\Import;
use App\ImportRecord;
use App\Item;
use App\Repositories\IFileRepository;
use Intervention\Image\Image;
use Symfony\Component\Console\Exception\LogicException;


abstract class AbstractImporter implements IImporter {

    /** @var callable[] */
    protected $sanitizers = [];

    /** @var callable[] */
    protected $filters = [];

    /** @var array */
    protected $mapping = [];

    /** @var array */
    protected $defaults = [];

    /** @var IFileRepository */
    protected $repository;

    /** @var int */
    protected $image_max_size = 800;

    /** @var string */
    protected $iipimg_url_format = 'https://www.webumenia.sk/fcgi-bin/iipsrv.fcgi?DeepZoom=%s.dzi';

    /** @var string */
    protected static $name;

    /**
     * @param IFileRepository $repository
     */
    public function __construct(IFileRepository $repository) {
        if (static::$name === null) {
            throw new LogicException(sprintf(
                '%s needs to define its $name static property',
                get_class($this)
            ));
        }

        $this->repository = $repository;
    }

    /**
     * @param array $record
     * @return mixed
     */
    abstract protected function getItemId(array $record);

    /**
     * @param array $record
     * @return string
     */
    abstract protected function getItemImageFilename(array $record);

    /**
     * @param string $csv_filename
     * @param string $image_filename
     * @return string
     */
    abstract protected function getItemIipImageUrl($csv_filename, $image_filename);

    public function import(Import $import, array $file)
    {
        $import_record = $this->createImportRecord(
            $import->id,
            Import::STATUS_IN_PROGRESS,
            date('Y-m-d H:i:s'),
            $file['basename']
        );

        $records = $this->repository->getFiltered(
            storage_path(sprintf('app/%s', $file['path'])),
            $this->filters,
            $this->options
        );

        $items = [];
        foreach ($records as $record) {
            $item = $this->importSingle($record, $import, $import_record);

            if (!$item) {
                continue;
            }

            $item->save();
            $items[] = $item;
        }

        $import_record->save();

        return $items;
    }

    public static function getName() {
        return static::$name;
    }

    /**
     * @param array $record
     * @param Import $import
     * @param ImportRecord $importRecord
     * @return Item|null
     */
    protected function importSingle(array $record, Import $import, ImportRecord $import_record) {
        try {
            $item = $this->createItem($record);
            $import_record->imported_items++;
        } catch (\Exception $e) {
            $import_record->wrong_items++;
            // todo log exception
            throw $e;
            return null;
        }

        $image_filename = $this->getItemImageFilename($record);

        $image_path = $this->getItemImagePath(
            $import,
            $import_record->filename,
            $image_filename
        );
        if ($image_path === false) {
            return $item;
        }

        $this->uploadImage($item, $image_path);
        $import_record->imported_images++;

        $remote_path = $this->getItemIipImageUrl($import_record->filename, $image_filename);
        if (!$this->testIipImageUrl($remote_path)) {
            return $item;
        }

        $item->iipimg_url = $remote_path;
        $import_record->imported_iip++;

        return $item;
    }

    /**
     * @param string $remote_path
     * @return bool
     */
    protected function testIipImageUrl($remote_path) {
        $iipimg_url = sprintf(
            $this->iipimg_url_format,
            $remote_path
        );

        return isValidURL($iipimg_url);
    }

    /**
     * @param int $import_id
     * @param string $status
     * @param string $started_at
     * @param string $filename
     * @return ImportRecord
     */
    protected function createImportRecord($import_id, $status, $started_at, $filename) {
        $import_record = new ImportRecord();
        $import_record->import_id = $import_id;
        $import_record->status = $status;
        $import_record->started_at = $started_at;
        $import_record->filename = $filename;

        return $import_record;
    }

    /**
     * @param array $record
     * @return Item
     */
    protected function createItem(array $record) {
        $id = $this->getItemId($record);

        $item = Item::firstOrNew(['id' => $id]);

        $record = array_map(function ($value) {
            return $this->sanitize($value);
        }, $record);

        $this->mapFields($item, $record);
        $this->applyCustomHydrators($item, $record);
        $this->setDefaultValues($item);

        return $item;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function sanitize($value) {
        foreach ($this->sanitizers as $sanitizer) {
            $value = $sanitizer($value);
        }

        return $value;
    }

    /**
     * @param Item $item
     * @param array $record
     */
    protected function mapFields(Item $item, array $record) {
        foreach ($record as $key => $value) {
            if (isset($this->mapping[$key])) {
                $mappedKey = $this->mapping[$key];
                $item->$mappedKey = $value;
            }
        }
    }

    /**
     * @param Item $item
     * @param array $record
     */
    protected function applyCustomHydrators(Item $item, array $record) {
        foreach ($item->getFillable() as $key) {
            $method_name = sprintf('hydrate%s', camel_case($key));
            if (method_exists($this, $method_name)) {
                $item->$key = $this->$method_name($record);
            }
        }
    }

    /**
     * @param Item $item
     * @param array $record
     */
    protected function setDefaultValues(Item $item) {
        foreach ($this->defaults as $key => $default) {
            if (!isset($item->$key)) {
                $item->$key = $default;
            }
        }
    }

    /**
     * @param Item $item
     * @param string $path
     * @return Image
     */
    protected function uploadImage(Item $item, $path) {
        $uploaded_image = \Image::make($path);

        // todo do not resize image here
        if ($uploaded_image->width() > $uploaded_image->height()) {
            $uploaded_image->widen($this->image_max_size);
        } else {
            $uploaded_image->heighten($this->image_max_size);
        }

        $item->removeImage();

        $save_as = $item->getImagePath($full = true);
        $uploaded_image->save($save_as);

        $item->has_image = true;
    }

    /**
     * @param Import $import
     * @param string $filename
     * @param array $record
     * @return string
     */
    protected function getItemImagePath(Import $import, $csv_filename, $image_filename) {
        $path = storage_path(sprintf(
            'app/import/%s/%s/%s*.{jpg,jpeg,JPG,JPEG}',
            $import->dir_path,
            basename($csv_filename, '.csv'),
            $image_filename
        ));

        $images = glob($path, GLOB_BRACE);
        return reset($images);
    }
}