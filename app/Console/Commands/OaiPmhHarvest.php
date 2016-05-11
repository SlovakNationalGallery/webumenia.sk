<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class OaiPmhHarvest extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'oai-pmh:harvest';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Harvest data for given set using OAI-PMH.';

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
		if ( ! $id =$this->option('id'))
        {
            $harvests = SpiceHarvesterHarvest::orderBy('id', 'ASC')->get();
            $this->info("Dostupné OAI-PMH sety:");
            foreach ($harvests as $h) {
            	echo " * $h->id [$h->type] $h->set_name \n";
            }
            $id = $this->ask('Zadaj ID setu, ktorý sa má harvestovať:');
        }
        $harvest = SpiceHarvesterHarvest::find($id);
        if (!$harvest) {
        	$this->error("Nenašiel sa set pre dané ID.");
        	return;
        }
        $reindex =$this->option('reindex');
        if ($reindex) {
        	$this->info("Je zapnutý reindex celého setu. Bude to trvať dlhšie.");
        	Input::merge(array('reindex' => true));
        }
        if ($this->option('start_date')) {
        	Input::merge(array('start_date' => $this->option('start_date')));
        }
        if ($this->option('end_date')) {
        	Input::merge(array('end_date' => $this->option('end_date')));
        }
        $this->comment("Spúšťa sa harvest pre set {$harvest->set_name}.");

        App::make('SpiceHarvesterController')->launch($id);
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
			array('id', null, InputOption::VALUE_OPTIONAL, 'Spice Harvester harvest ID.', null),
			array('reindex', null, InputOption::VALUE_OPTIONAL, 'Re-harvest all records.', false),
			array('start_date', null, InputOption::VALUE_OPTIONAL, 'Specify start date in YYYY-MM-DD.', null),
			array('end_date', null, InputOption::VALUE_OPTIONAL, 'Specify end date in YYYY-MM-DD.', null),
		);
	}

}
