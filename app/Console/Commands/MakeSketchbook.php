<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeSketchbook extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'sketchbook:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Make PDF sketchbook for item with given ID.';

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
        $ids = [];
        $id = null;

        $all =$this->option('all');

        if ($all) {
        	$this->info("Je zapnuté sťahovanie všetkých skicárov. Bude to trvať dlhšie.");
        	$ids = Sketchbook::lists('id');
        } elseif ( ! $id =$this->option('id'))
        {
            $sketchbooks = Sketchbook::orderBy('order', 'ASC')->get();
            $this->info("Dostupné skicare:");
            foreach ($sketchbooks as $s) {
            	$generated_at = ($s->generated_at) ? " (vygenerovaný: $s->generated_at)" : "(neexistuje)";
            	echo " * $s->id [$s->item_id] $s->title $generated_at \n";
            }
            $id = $this->ask('Zadaj ID skicáru, ktorý sa má vygenerovať:');
        }

        if(!in_array($id, $ids)){
            array_push($ids, $id);
        }

        foreach ($ids as $i=>$id) {
        	if ( $i >= 0) $this->makeSketchbook($id);
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
			array('id', null, InputOption::VALUE_OPTIONAL, 'Item ID.', null),
			array('all', null, InputOption::VALUE_OPTIONAL, 'Make all available sketchbooks.', false),
		);
	}

	private function makeSketchbook($id)
	{
		$sketchbook = Sketchbook::find($id);
		if (!$sketchbook) {
			$this->error("Nenašiel sa skicár pre dané ID.");
			return;
		}

		$this->comment("Generuje sa sketchbook pre {$sketchbook->title}.");

		App::make('SkicareController')->downloadAllPages($id);
	}

}
