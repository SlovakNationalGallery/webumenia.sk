<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\ResponsiveImages\Jobs\GenerateResponsiveImagesJob;

class MediaLibraryRegenerateResponsiveImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media-library:regenerate-responsive-images
    {--only-missing : Regenerate only for missing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerates responsive images for media';

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
     * @return int
     */
    public function handle()
    {
        $query = Media::query();
        if ($this->option('only-missing')) $query->whereJsonLength('responsive_images', 0);
        
        foreach ($query->lazy() as $media) {
            Bus::dispatch(new GenerateResponsiveImagesJob($media));
        }
    }
}
