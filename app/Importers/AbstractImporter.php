<?php

namespace App\Importers;

use App\Import;
use App\ImportRecord;
use App\Item;
use App\ItemImage;
use App\Repositories\IFileRepository;
use Doctrine\Common\Collections\Collection;
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
    abstract protected function getItemImageFilenameFormat(array $record);

    public function import(Import $import, array $file)
    {
        $import_record = $this->createImportRecord(
            $import->id,
            Import::STATUS_IN_PROGRESS,
            date('Y-m-d H:i:s'),
            $file['basename']
        );

        $import_record->save();

        $records = $this->repository->getFiltered(
            storage_path(sprintf('app/%s', $file['path'])),
            $this->filters,
            $this->options
        );

        $items = [];

        foreach ($records as $record) {
            try {
                $item = $this->importSingle($record, $import, $import_record);
                $item->push();
                $items[] = $item;
                $import_record->imported_items++;
            } catch (\Exception $e) {
                $import->status=Import::STATUS_ERROR;
                $import->save();

                $import_record->wrong_items++;
                $import_record->status=Import::STATUS_ERROR;
                $import_record->error_message=$e->getMessage();

                continue;
            } finally {
                $import_record->save();
            }
        }

        if ($import_record->status != Import::STATUS_ERROR) {
            $import_record->status = Import::STATUS_COMPLETED;
        }

        $import_record->completed_at=date('Y-m-d H:i:s');
        $import_record->save();

        return $items;
    }

    public static function getName() {
        return static::$name;
    }

    public function getOptions() {
        return $this->options;
    }

    /**
     * @param array $record
     * @param Import $import
     * @param ImportRecord $importRecord
     * @return Item|null
     */
    protected function importSingle(array $record, Import $import, ImportRecord $import_record) {
        $item = $this->createItem($record);

        $image_filename_format = $this->getItemImageFilenameFormat($record);

        $jpg_paths = $this->getImageJpgPaths(
            $import,
            $import_record->filename,
            $image_filename_format
        );

        foreach ($jpg_paths as $jpg_path) {
            $this->uploadImage($item, $jpg_path);
            $import_record->imported_images++;
        }

        $jp2_paths = $this->getImageJp2Paths(
            $import,
            $import_record->filename,
            $image_filename_format
        );

        foreach ($jp2_paths as $jp2_path) {
            $jp2_relative_path = $this->getImageJp2RelativePath($jp2_path);
            if ($image = ItemImage::where('iipimg_url', $jp2_relative_path)->first()) {
                continue;
            }

            $image = new ItemImage();
            $image->item_id = $item->getKey();
            $item->images->add($image);
            $image->iipimg_url = $jp2_relative_path;
            $import_record->imported_iip++;
        }

        return $item;
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
     */
    protected function uploadImage(Item $item, $path) {
        $uploaded_image = \Image::make($path);

        // @TODO do not resize image here
        if ($uploaded_image->width() > $uploaded_image->height()) {
            $uploaded_image->widen(800, function ($constraint) {
                $constraint->upsize();
            });
        } else {
            $uploaded_image->heighten(800, function ($constraint) {
                $constraint->upsize();
            });
        }

        $item->removeImage();

        $save_as = $item->getImagePath($full = true);
        $uploaded_image->save($save_as);

        $item->has_image = true;
    }

    /**
     * @param Import $import
     * @param string $csv_filename
     * @param string $image_filename_format
     * @return string[]
     */
    protected function getImageJpgPaths(Import $import, $csv_filename, $image_filename_format) {
        $path = storage_path(sprintf(
            'app/import/%s/%s/%s.{jpg,jpeg,JPG,JPEG}',
            $import->dir_path,
            pathinfo($csv_filename, PATHINFO_FILENAME),
            $image_filename_format
        ));

        $paths = glob($path, GLOB_BRACE);

        usort($paths, function ($a, $b) {
            return filemtime($a) - filemtime($b);
        });

        return $paths;
    }

    /**
     * @param Import $import
     * @param string $csv_filename
     * @param string $image_filename_format
     * @return string[]
     */
    protected function getImageJp2Paths(Import $import, $csv_filename, $image_filename_format) {
        $path = sprintf(
            '%s/%s/%s/%s.jp2',
            config('importers.iip_base_path'),
            $import->iip_dir_path,
            pathinfo($csv_filename, PATHINFO_FILENAME),
            $image_filename_format
        );

        $paths = glob($path, GLOB_BRACE);

        usort($paths, function ($a, $b) {
            return filemtime($a) - filemtime($b);
        });

        return $paths;
    }

    /**
     * @param string $jp2_path
     * @return string
     */
    protected function getImageJp2RelativePath($jp2_path) {
        return mb_substr($jp2_path, mb_strlen(config('importers.iip_base_path')) + 1);
    }
}