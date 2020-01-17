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
    public function handle()
    {
        $items_without_images_query = Item::hasImage(false);
        $pocet = $items_without_images_query->count();

        if (! $this->confirm("Naozaj spustit stahovanie obrazkov pre {$pocet} diel? [yes|no]", true)) {
            $this->comment('Tak dovidenia.');
            return;
        }

        $logFile = 'image_download.log';
        $this->log = new \Monolog\Logger('image_download');
        $this->log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path().'/logs/'.$logFile, \Monolog\Logger::WARNING));

        $i = 0;
        $failures = 0;

        $items_without_images_query->chunkById(200, function ($items) use (&$i, &$failures) {
            // $items->load('authorities');
            foreach ($items as $item) {
                $success = false;

                if ($item::hasImageForId($item->id)) {
                    $item->has_image = true;
                    $item->save();
                    $success = true;
                } else {
                    try {
                        $success = $item->downloadImage();
                    } catch (\Exception $e) {
                        $this->log->addError($item->img_url . ': ' . $e->getMessage());
                        app('sentry')->captureException($e);
                    }
                }

                if ($success) {
                    $i++;
                    if (App::runningInConsole() && $i % 100 == 0) {
                        echo date('h:i:s') . " " . $i . "\n";
                    }
                } else {
                    $failures++;
                }
            }
        });
        $message = 'Pre ' . $i . ' diel boli stiahnute obrazky';
        if ($failures > 0) {
            $message .= "\n" . $failures . ' diela nemajú žiadny obrázok alebo sa nepodarilo sťahovať zdroj';
        }
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
}
