<?php



namespace App\Console\Commands;

use App\Item;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateTags extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tags:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate tags from Items to polymorfic table.';

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
        $this->line('Teraz prebehne migracia tagov.');
        $pocet_tagov = 0;

        Item::chunk(200, function ($items) use (&$pocet_tagov) {
        
            foreach ($items as $item) {
                if (!empty($item->subject)) {
                    $tags = $item->subjects;
                    $pocet_tagov += count($tags);
                    $item->tag($tags);
                }
            }
        });
        $this->info('Bolo zmigrovanych ' . $pocet_tagov . ' tagov');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
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
            
        );
    }
}
