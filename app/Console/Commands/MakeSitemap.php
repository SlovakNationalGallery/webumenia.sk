<?php

namespace App\Console\Commands;

use App\Article;
use App\Authority;
use App\Collection;
use App\Item;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url as SitemapUrl;

class MakeSitemap extends Command
{
    const SITEMAPS_DIR = 'sitemaps/';
    protected $max_entries = 50000; // max entries per sitemap. according google spec max is 50 000
    protected $available_models = [
        Article::class,
        Authority::class,
        Collection::class,
        Item::class,
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

        // Miscelaneous
        Sitemap::create()
            ->add(
                SitemapUrl::create('/')
                    ->setLastModificationDate($this->getLastModified('home/index.blade.php'))
                    ->setChangeFrequency(SitemapUrl::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1)
            )
            ->add(
                SitemapUrl::create('/informacie')
                    ->setLastModificationDate($this->getLastModified('informacie.blade.php'))
                    ->setChangeFrequency(SitemapUrl::CHANGE_FREQUENCY_YEARLY)
                    ->setPriority(0.8)
            )
            ->writeToFile(public_path(self::SITEMAPS_DIR . 'misc.xml'));

        $this->addSitemap(self::SITEMAPS_DIR . 'misc');

        // Model-based sitemaps
        foreach ($this->available_models as $model) {
            $this->makeSitemapForModel($model);
        }

        $sitemapIndex = SitemapIndex::create();
        foreach ($this->sitemaps as $sitemap) {
            $sitemapIndex->add(URL::to($sitemap));
        }

        $sitemapIndex->writeToFile(public_path('sitemap.xml'));

        $this->comment('sitemap.xml');

        $this->info('Sitemapa bola vygernerovaná úspešne.');
    }

    private function addSitemap($sitemap_name)
    {
        $sitemap_name = $sitemap_name . '.xml';
        $this->sitemaps[] = $sitemap_name;
        $this->comment($sitemap_name);
    }

    private function makeSitemapForModel(
        $model,
        $priority = 0.5, // Default according to https://www.sitemaps.org/protocol.html
        $freq = SitemapUrl::CHANGE_FREQUENCY_WEEKLY
    ) {
        $entryCounter = 0;
        $sitemapPartNumber = 1;
        $sitemap = Sitemap::create();

        foreach ($model::lazy() as $entry) {
            // Skip unpublished entries
            if ($model == Article::class && !$entry->publish) {
                continue;
            }

            if ($model == Collection::class && !$entry->is_published) {
                continue;
            }

            $url = SitemapUrl::create($entry->getUrl())
                ->setLastModificationDate($entry->updated_at)
                ->setPriority($priority)
                ->setChangeFrequency($freq);

            if ($entry->has_image) {
                $url->addImage($entry->getImagePath());
            }

            $sitemap->add($url);
            $entryCounter++;

            // Write & "rotate" the sitemap once we have reached limit
            if ($entryCounter >= $this->max_entries) {
                $this->writeSitemapForModel($sitemap, $model, $sitemapPartNumber);

                // Create a new sitemap instance & reset counter
                $sitemap = Sitemap::create();
                $sitemapPartNumber++;
                $entryCounter = 0;
            }
        }

        // Write last part
        $this->writeSitemapForModel($sitemap, $model, $sitemapPartNumber);
    }

    private function writeSitemapForModel(Sitemap $sitemap, $model, int $partNumber): void
    {
        $fileName =
            Str::of($model)
                ->classBasename()
                ->plural()
                ->lower() .
            '-' .
            $partNumber;

        $sitemap->writeToFile(public_path(self::SITEMAPS_DIR . $fileName . '.xml'));
        $this->addSitemap(self::SITEMAPS_DIR . $fileName);
    }

    private function getLastModified($file)
    {
        $fileLastModTime = filemtime(resource_path() . '/views/' . $file);
        return Carbon::createFromTimeStamp($fileLastModTime);
    }
}
