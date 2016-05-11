<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TestMemory extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'test:memory';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Valid memory limit test.';

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
		$this->line("memory limit is set to ". ini_get("memory_limit"));
		for($i=10;$i<1000;$i+=50){
		    $limit = $i.'M';
		    // ini_set('memory_limit', $limit); 
		    $this->info("set memory_limit to {$limit}");
		    // echo "memory limit is ". ini_get("memory_limit")."\n";
	    	$this->tryAlloc($i-10);
		    
		}
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
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

	private function tryAlloc($megabyte){
	    echo "try allocating {$megabyte} megabyte...";
	    try {
		    $dummy = str_repeat("-",1048576*$megabyte);
	    } catch (Exception $e) {
	    	throw new \Exception($e->getMessage());
	    }

	    echo "pass.";
	    echo "Usage: " . memory_get_usage(true)/1048576; 
	    echo " Peak: " . memory_get_peak_usage(true)/1048576;
	    echo "\n";
	}   


}
