<?php



namespace App\Console\Commands;

use App\Item;
use Illuminate\Support\Facades\App;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class OaiPmhDownloadImages extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'oai-pmh:download-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download images for items.';

    protected $log;

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
        $pocet = Item::where('img_url', '!=', '')->where('has_image', '=', 0)->count();

        if (! $this->confirm("Naozaj spustit stahovanie obrazkov pre {$pocet} diel? [yes|no]", true)) {
            $this->comment('Tak dovidenia.');
            return;
        }

        $logFile = 'image_download.log';
        $this->log = new \Monolog\Logger('image_download');
        $this->log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path().'/logs/'.$logFile, \Monolog\Logger::WARNING));

        $i = 0;

        Item::where('img_url', '!=', '')->where('has_image', '=', 0)->chunk(200, function ($items) use (&$i) {
            // $items->load('authorities');
            foreach ($items as $item) {
                if ($item::hasImageForId($item->id) || $this->downloadImage($item)) {
                    $i++;
                    $item->has_image = true;
                    $item->save();
                }
                
                if (App::runningInConsole()) {
                    if ($i % 100 == 0) {
                        echo date('h:i:s'). " " . $i . "\n";
                    }
                }
            }
        });
        $message = 'Pre ' . $i . ' diel boli stiahnute obrazky';
        $this->comment($message);
        return true;
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

    private function downloadImage($item)
    {
        $file = $item->img_url;
        try {
            $data = file_get_contents($file);
        } catch (\Exception $e) {
            $this->log->addError($item->img_url . ': ' . $e->getMessage());
            return false;
        }
        
        $full = true;
        if ($new_file = $item->getImagePath($full)) {
            file_put_contents($new_file, $data);
            return true;
        }
        return false;
    }
}
