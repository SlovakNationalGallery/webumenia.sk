<?php

namespace App\Jobs;

use App\Import;
use App\ImportRecord;
use App\Repositories\CsvRepository;
use App\Jobs\Job;
use App\Matchers\AuthorityMatcher;
use DateTime;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use SplFileInfo;

class ImportCsv extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $import;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AuthorityMatcher $authorityMatcher, Translator $translator)
    {
        $this->import->status = Import::STATUS_IN_PROGRESS;
        $this->import->started_at = new DateTime();
        $this->import->save();

        try {
            $reflection = new \ReflectionClass($this->import->class_name);
            if (!$reflection->isInstantiable()) {
                throw new \Exception('Class is not instantiable');
            }
            $importer = new $this->import->class_name(
                $authorityMatcher,
                new CsvRepository(),
                $translator
            );
        } catch (\Exception $e) {
            if (app()->runningInConsole()) {
                echo "Nenašiel sa importer pre dané ID.\n";
            }
            app('sentry')->captureException($e);
            return;
        }

        $this->import->csvFiles()->each(function (SplFileInfo $file) use ($importer) {
            if (app()->runningInConsole()) {
                echo "Spúšťa sa import pre {$file->getBasename()}.\n";
            }

            $import_record = $this->import->records()->create([
                'filename' => $file->getBasename(),
            ]);
            $stream = $import_record->readStream($file);
            $importer->import($import_record, $stream);
        });

        if ($this->import->status != Import::STATUS_ERROR) {
            $this->import->status = Import::STATUS_COMPLETED;
        }

        $this->import->completed_at = new DateTime();
        $this->import->save();
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed()
    {
        // @todo - handle fail (send notification?)
    }
}
