<?php



namespace App\Console\Commands;

use Illuminate\Support\Facades\App;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ReindexElasticsearch extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'es:reindex';

    protected $available_types = ['items', 'authorities'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex elasticsearch from database.';

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
        if (!$type = $this->argument('type')) {
            $this->info("Dostupné ES typy:");
            foreach ($this->available_types as $available_type) {
                echo " * [$available_type] \n";
            }
            $type[] = $this->ask('Zadaj názov typu, ktorý sa má reindexovať z databázy:');
        }

        foreach ($type as $t) {
            if (in_array($t, $this->available_types)) {
                $this->info("Spúšťam reindex pre typ: " . $t);
                $controller = ucfirst(str_singular($t));
                App::make($controller . 'Controller')->reindex();
            } else {
                $this->error('Neznámy typ');
            }
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
            array('type', InputOption::VALUE_OPTIONAL, 'ElasticSearch type [items|authorities].', null),
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
            // array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }
}
