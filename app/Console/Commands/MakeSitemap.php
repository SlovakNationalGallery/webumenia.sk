<?php



namespace App\Console\Commands;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MakeSitemap extends Command
{
    const SITEMAPS_DIR = 'sitemaps/';
    protected $max_entries = 50000; // max entries per sitemap. according google spec max is 50 000
    protected $available_models = [
        'Article',
        'Authority',
        'Collection',
        'Item',
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sitemap:make';
    protected $sitemaps = [];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap for the website.';

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
        $this->info('Spúšťam generovanie sitemap. Bude to chvíľu trvať.');

        /*** MISC ****/
        $sitemap_misc = App::make("sitemap");

        $sitemap_misc->add(URL::to(''), $this->getLastModified('intro.blade.php'), '1.0', 'weekly');
        $sitemap_misc->add(URL::to('informacie'), $this->getLastModified('informacie.blade.php'), '0.8');

        $sitemap_misc->store('xml', self::SITEMAPS_DIR . 'misc');
        $this->addSitemap(self::SITEMAPS_DIR . 'misc');

        /**** vytvorit sitemapy pre kazdy (povoleny) model *****/
        foreach ($this->available_models as $model) {
            $this->makeSitemapForModel($model);
        }

        $sitemap = App::make("sitemap");
        foreach ($this->sitemaps as $sitemap_name) {
            $sitemap->addSitemap(URL::to($sitemap_name));
        }
        $sitemap->store('sitemapindex', 'sitemap');
        $this->comment('sitemap');

        $this->info('Sitemapa bola vegernerovaná úspešne.');
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

    private function addSitemap($sitemap_name)
    {
        $sitemap_name = $sitemap_name . '.xml';
        $this->sitemaps[] = $sitemap_name;
        $this->comment($sitemap_name);
    }

    private function makeSitemapForModel($model, $priority = null, $freq = null)
    {
        $i = 0;
        $sitemap_count = 0;
        $sitemap = App::make("sitemap");
        $namespaced_model = '\App\\'. $model;

        $namespaced_model::chunk(200, function ($entries) use (&$sitemap, &$i, &$sitemap_count, &$model, &$priority, &$freq) {
            foreach ($entries as $entry) {
                // preskocit clanky a kolekcie, ktore niesu vypublikovane
                if (($model == 'Article' || $model == 'Collection') && (!$entry->publish)) {
                    continue;
                }
                $images = [];
                if ($entry->has_image) {
                    $images[] = ['url' => URL::to($entry->getImagePath()), 'title' => $entry->title];
                }
                $sitemap->add($entry->getUrl(), $entry->updated_at, $priority, $freq, $images);
                $i++;
                if ($i >= $this->max_entries) {
                    $sitemap_name = self::SITEMAPS_DIR . Str::plural(strtolower($model)) . '-' . ($sitemap_count+1);
                    $sitemap->store('xml', $sitemap_name);
                    $this->addSitemap($sitemap_name);
                    $sitemap = App::make("sitemap"); // vytvori nanovo
                    $sitemap_count++;
                    $i = 0;
                }
            }
        });

        // treba ulozit poslednu sitemapu
        if ($i > 0) {
            $sitemap_name = self::SITEMAPS_DIR . Str::plural(strtolower($model)) . '-' . ($sitemap_count+1);
            $sitemap->store('xml', $sitemap_name);
            $this->addSitemap($sitemap_name);
        }

    }

    private function getLastModified($file)
    {
        $fileLastModTime = filemtime(resource_path() . '/views/' . $file);
        return Carbon::createFromTimeStamp($fileLastModTime);
    }
}
