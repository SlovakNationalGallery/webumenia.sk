<?php

namespace App\Jobs;

use App\Import;
use App\Importers\AbstractImporter;
use App\Importers\IImporter;
use App\Repositories\CsvRepository;


use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
    public function handle()
    {
        $this->import->status = Import::STATUS_IN_PROGRESS;
        $this->import->save();

        try {
            $reflection = new \ReflectionClass($this->import->class_name);
            if (!$reflection->isInstantiable()) {
                throw new \Exception('Class is not instantiable');
            }
            $importer = new $this->import->class_name(new CsvRepository());
        } catch (\Exception $e) {
            if (\App::runningInConsole()) {
                echo "Nenašiel sa importer pre dané ID.\n";
            }
            return;
        }

        foreach ($this->import->files as $file) {
            if (\App::runningInConsole()) {
                echo "Spúšťa sa import pre {$file['path']}.\n";
            }
            $importer->import($this->import, $file);
        }

        if ($this->import->status != Import::STATUS_ERROR) {
            $this->import->status = Import::STATUS_COMPLETED;
        }

        $this->import->completed_at = date('Y-m-d H:i:s');
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
