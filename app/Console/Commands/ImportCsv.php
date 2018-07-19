<?php



namespace App\Console\Commands;

use App\Import;
use App\Repositories\CsvRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportCsv extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'csv:import {--id=} {--queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CSV files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if (! $id =$this->option('id')) {
            $imports = Import::orderBy('id', 'ASC')->get();
            $this->info("Dostupné CSV importy:");
            foreach ($imports as $i) {
                echo " * $i->id -> $i->name";
                if ($i->dir_path) {
                    echo " (/storage/imports/$i->dir_path)";
                }
                echo "\n";
            }
            $id = $this->ask('Zadaj ID importu, ktorý sa má spustiť');
        }

        if ($id == '*') {
            $imports = Import::all();
        } else {
            $imports = [Import::find($id)];

            if (!$imports[0]) {
                $this->error("Nenašiel sa set pre dané ID.");
                return;
            }
        }

        foreach ($imports as $import) {
            dispatch(new \App\Jobs\ImportCsv($import));
        }

        $this->comment("Dokoncene");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            // array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('id', null, InputOption::VALUE_OPTIONAL, 'Import ID.', null),
            // array('reindex', null, InputOption::VALUE_OPTIONAL, 'Re-Import all records.', false),
            // array('start_date', null, InputOption::VALUE_OPTIONAL, 'Specify start date in YYYY-MM-DD.', null),
            // array('end_date', null, InputOption::VALUE_OPTIONAL, 'Specify end date in YYYY-MM-DD.', null),
        );
    }
}
