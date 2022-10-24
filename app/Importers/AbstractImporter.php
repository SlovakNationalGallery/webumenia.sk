<?php

namespace App\Importers;

use App\Import;
use App\ImportRecord;
use App\Item;
use App\Matchers\AuthorityMatcher;
use App\Repositories\IFileRepository;
use DateTime;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SplFileInfo;
use Symfony\Component\Console\Exception\LogicException;

abstract class AbstractImporter
{
    /** @var callable[] */
    protected $sanitizers = [];

    /** @var callable[] */
    protected $filters = [];

    /** @var array */
    protected $mapping = [];

    /** @var array */
    protected $defaults = [];

    /** @var AuthorityMatcher */
    protected $authorityMatcher;

    /** @var IFileRepository */
    protected $repository;

    /** @var Translator */
    protected $translator;

    /** @var int */
    protected $image_max_size = 800;

    /** @var array */
    protected static $options = [
        'delimiter' => ',',
        'enclosure' => '"',
        'escape' => '\\',
        'newline' => "\n",
    ];

    /** @var string */
    protected static $name;

    public function __construct(
        AuthorityMatcher $authorityMatcher,
        IFileRepository $repository,
        Translator $translator
    ) {
        if (static::$name === null) {
            throw new LogicException(
                sprintf('%s needs to define its $name static property', get_class($this))
            );
        }

        $this->authorityMatcher = $authorityMatcher;
        $this->repository = $repository;
        $this->translator = $translator;
        $this->init();
    }

    protected function init()
    {
    }

    abstract protected function getItemId(array $record);

    abstract protected function getItemImageFilenameFormat(array $record): string;

    public function import(Import $import, SplFileInfo $file): array
    {
        $import_record = $import->records()->create([
            'status' => Import::STATUS_IN_PROGRESS,
            'started_at' => new DateTime(),
            'filename' => $file->getBasename(),
        ]);

        $stream = $import->readStream($file);
        $records = $this->repository->getFiltered($stream, $this->filters, static::$options);

        $items = [];
        foreach ($records as $record) {
            try {
                if ($item = $this->importSingle($record, $import_record)) {
                    $item->push();
                    $items[] = $item;
                    $import_record->imported_items++;
                }
            } catch (\Exception $e) {
                $import->status = Import::STATUS_ERROR;
                $import->save();

                $import_record->wrong_items++;
                $import_record->status = Import::STATUS_ERROR;
                $import_record->error_message = $e->getMessage();
                app('sentry')->captureException($e);

                break;
            } finally {
                $import_record->save();
            }
        }

        if ($import_record->status != Import::STATUS_ERROR) {
            $import_record->status = Import::STATUS_COMPLETED;
        }

        $import_record->completed_at = new DateTime();
        $import_record->save();

        return $items;
    }

    public static function getName(): string
    {
        return static::$name;
    }

    public static function getOptions(): array
    {
        return static::$options;
    }

    protected function importSingle(array $record, ImportRecord $import_record): ?Item
    {
        $item = $this->createItem($record);

        $image_filename_format = $this->getItemImageFilenameFormat($record);

        $jpgFile = $this->getJpgFile($import_record, $image_filename_format);
        if ($jpgFile) {
            $stream = $import_record->readStream($jpgFile);
            $item->saveImage($stream);
            $import_record->imported_images = 1;
        }

        $this->getJp2Files($import_record, $image_filename_format)
            ->filter(
                fn(SplFileInfo $jp2File) => !$item
                    ->images()
                    ->where('iipimg_url', $jp2File)
                    ->first()
            )
            ->each(function (SplFileInfo $jp2File) use ($item, $import_record) {
                $item->images()->create(['iipimg_url' => $jp2File]);
                $import_record->imported_iip++;
            });

        $ids = $this->authorityMatcher
            ->matchAll($item)
            ->filter(function ($authorities) {
                return $authorities->count() === 1;
            })
            ->flatten()
            ->pluck('id');

        $changes = $item->authorities()->syncWithoutDetaching($ids);
        $item
            ->authorities()
            ->updateExistingPivot($changes['attached'], ['automatically_matched' => true]);

        return $item;
    }

    protected function createItem(array $record): Item
    {
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

    protected function sanitize($value)
    {
        foreach ($this->sanitizers as $sanitizer) {
            $value = $sanitizer($value);
        }

        return $value;
    }

    protected function mapFields(Item $item, array $record): void
    {
        foreach ($this->mapping as $property => $column) {
            if (isset($record[$column])) {
                $item->$property = $record[$column];
            }
        }
    }

    protected function applyCustomHydrators(Item $item, array $record): void
    {
        foreach ($item->getFillable() as $key) {
            $method_name = sprintf('hydrate%s', Str::camel($key));
            if (method_exists($this, $method_name)) {
                // translatable attribute
                if (in_array($key, $item->translatedAttributes)) {
                    foreach (config('translatable.locales') as $locale) {
                        $value = $this->$method_name($record, $locale);
                        if ($value) {
                            $item->translateOrNew($locale)->$key = $value;
                        }
                    }
                    // other attribute
                } else {
                    $item->$key = $this->$method_name($record);
                }
            }
        }
    }

    protected function setDefaultValues(Item $item): void
    {
        $useTranslationFallback = $item->getUseTranslationFallback();
        $item->setUseTranslationFallback(false);
        foreach ($this->defaults as $key => $default) {
            if (!isset($item->$key)) {
                $item->$key = $default;
            }
        }
        $item->setUseTranslationFallback($useTranslationFallback);
    }

    protected function getJpgFile(
        ImportRecord $import_record,
        string $image_filename_format
    ): ?SplFileInfo {
        return $import_record
            ->files()
            ->first(
                fn(SplFileInfo $file) => preg_match(
                    sprintf('#^%s\.(jpg|jpeg)$#i', $image_filename_format),
                    $file->getBasename()
                )
            );
    }

    protected function getJp2Files(
        ImportRecord $import_record,
        string $image_filename_format
    ): Collection {
        $dir = sprintf(
            '%s/%s',
            $import_record->import->iip_dir_path,
            pathinfo($import_record->filename, PATHINFO_FILENAME)
        );
        $files = Storage::disk('DG_PUBLIC_IS')->files($dir);
        return collect($files)
            ->map(fn(string $file) => new SplFileInfo($file))
            ->filter(
                fn(SplFileInfo $file) => preg_match(
                    sprintf('#^%s\.jp2$#', $image_filename_format),
                    $file->getBasename()
                )
            );
    }
}
