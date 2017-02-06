<?php



namespace App\Console\Commands;

use App\Import;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
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
    protected $name = 'csv:import';

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
            $id = $this->ask('Zadaj ID importu, ktorý sa má spustiť:');
        }
        $import = Import::find($id);
        if (!$import) {
            $this->error("Nenašiel sa set pre dané ID.");
            return;
        }
        // $reindex =$this->option('reindex');
        // if ($reindex) {
        //     $this->info("Je zapnutý reindex celého importu. Bude to trvať dlhšie.");
        //     Input::merge(array('reindex' => true));
        // }
        // if ($this->option('start_date')) {
        //     Input::merge(array('start_date' => $this->option('start_date')));
        // }
        // if ($this->option('end_date')) {
        //     Input::merge(array('end_date' => $this->option('end_date')));
        // }
        $this->comment("Spúšťa sa import pre {$import->name}.");

        App::make('\App\Http\Controllers\ImportController')->launch($id);
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
