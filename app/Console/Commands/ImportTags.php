<?php



namespace App\Console\Commands;

use Maatwebsite\Excel\Facades\Excel;
use App\Item;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportTags extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tags:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import tags from CSV dump from webumenia.sk';

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
        $csv_path = $this->argument('csv_path');
        $this->line('Importujem tagy z ' . $csv_path);
        $count_all = 0;
        $count_imported = 0;
        $host = $this;
        try {
            Excel::filter('chunk')->load($csv_path)->noHeading()->chunk(250, function ($results) use ($host, &$count_all, &$count_imported) {
            
                foreach ($results as $row) {
                    $count_all++;
                    $item = Item::find($row[4]);
                    if ($item) {
                        $tag = $row[2];
                        if ($host->validateTag($tag)) {
                            $count_imported++;
                            $item->tag($tag);
                            // $item->index(); //reindex to ES
                        }
                    }
                }

            });
        } catch (\Exception $e) {
            $this->error('Error: '.  $e->getMessage());
            die();
        }
        $this->info('Bolo importovanych ' . $count_imported . ' z ' . $count_all . ' tagov');

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('csv_path', InputArgument::REQUIRED, 'An argument with CSV file path.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

    /**
     * Validate the string if is suitable for tag
     *
     * @return boolean
     */
    private function validateTag($string)
    {
        if (empty($string)) {
            return false;
        }
        if (strpos($string, 'http')!== false) {
            return false;
        }
        if (is_numeric($string)) {
            return false;
        }

        return true;
    }
}
