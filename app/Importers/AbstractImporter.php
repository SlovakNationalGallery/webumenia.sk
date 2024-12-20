<?php

namespace App\Importers;

use App\Import;
use App\ImportRecord;
use App\Item;
use App\Matchers\AuthorityMatcher;
use App\Repositories\IFileRepository;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SplFileInfo;

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

    protected \SplObjectStorage $iipFilesMap;

    public function __construct(
        AuthorityMatcher $authorityMatcher,
        IFileRepository $repository,
        Translator $translator
    ) {
        $this->authorityMatcher = $authorityMatcher;
        $this->repository = $repository;
        $this->translator = $translator;
        $this->iipFilesMap = new \SplObjectStorage();
        $this->init();
    }

    protected function init()
    {
    }

    abstract protected function getItemId(array $record);

    abstract protected function getItemImageFilenameFormat(array $record): string;

    public function import(ImportRecord $import_record, $stream): array
    {
        $import_record
            ->fill([
                'status' => ImportRecord::STATUS_IN_PROGRESS,
                'started_at' => new DateTime(),
            ])
            ->save();

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
                $import_record->import->status = Import::STATUS_ERROR;
                $import_record->import->save();

                $import_record->wrong_items++;
                $import_record->status = ImportRecord::STATUS_ERROR;
                $import_record->error_message .= $e->getMessage() . PHP_EOL;
                app('sentry')->captureException($e);
            } finally {
                $import_record->save();
            }
        }

        if ($import_record->status != ImportRecord::STATUS_ERROR) {
            $import_record->status = ImportRecord::STATUS_COMPLETED;
        }

        $import_record->completed_at = new DateTime();
        $import_record->save();

        return $items;
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
            $lastModified = $import_record->lastModified($jpgFile);
            $lastStartedAt = $import_record->import
                ->records()
                ->completed()
                ->where('filename', '=', $import_record->filename)
                ->max('started_at');

            if (Carbon::createFromTimestamp($lastModified) > Carbon::make($lastStartedAt)) {
                $stream = $import_record->readStream($jpgFile);
                $item->saveImage($stream);
                $import_record->imported_images++;
            }
        }

        $jp2Files = $this->getJp2Files($import_record, $image_filename_format);
        $jp2Files
            ->each(function (SplFileInfo $jp2File, int $index) use ($item, $import_record) {
                if ($image = $item->images()->where('iipimg_url', $jp2File)->first()) {
                    $image->update(['order_column' => $index]);
                } else {
                    $item->images()->create([
                        'iipimg_url' => $jp2File,
                        'order_column' => $index,
                    ]);
                    $import_record->imported_iip++;
                }
            });
        $item
            ->images()
            ->whereNotIn('iipimg_url', $jp2Files)
            ->delete();

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
        $this->iipFilesMap[$import_record] ??= $import_record->iipFiles();
        return $this->iipFilesMap[$import_record]
            ->filter(
                fn(SplFileInfo $file) => preg_match(
                    sprintf('#^%s\.jp[2f]$#', $image_filename_format),
                    $file->getBasename()
                )
            )
            ->sort(SORT_NATURAL);
    }
}
