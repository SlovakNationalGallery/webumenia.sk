<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ReindexElasticsearch extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'es:reindex';

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
		if ( ! $type =$this->argument('type'))
        {
            $this->info("Dostupné ES typy:");
        	echo " * [items] \n";
        	echo " * [authorities] \n";
            $type = $this->ask('Zadaj názov typu, ktorý sa má reindexovať z databázy:');

            $controller = ucfirst(str_singular($type));
        }
	    App::make($controller . 'Controller')->reindex();

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
