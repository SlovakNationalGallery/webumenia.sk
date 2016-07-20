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
    protected $signature = 'es:reindex {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex elasticsearch from database.';

    protected $available_types = ['items', 'authorities'];

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
            $type = $this->choice('Which type to reindex?', $this->available_types);
        }

        $this->info("Spúšťam reindex pre typ: " . $type);
        $controller = '\App\Http\Controllers\\'.ucfirst(str_singular($type));
        App::make($controller . 'Controller')->reindex();

        $this->comment("Dokoncene");
    }

}
