<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Item;

class GenerateImageRatios extends Command
{
    protected $signature = 'images:generate-ratios';
    protected $description = 'Generate image ratios for each Item';

    public function handle()
    {
        $itemsWithoutImageRatioQuery = Item::whereNull('image_ratio');
        $itemsCount = $itemsWithoutImageRatioQuery->count();
        $this->output->progressStart($itemsCount);

        $chunkSize = 100;
        $count = 0;

        $itemsWithoutImageRatioQuery->chunk($chunkSize, function ($items) use (&$count) {
            foreach ($items as $item) {
                if (!$item->hasImageForId($item->id)) {
                    continue;
                }

                $path = $item->getImagePath(true);
                $image = \Image::make($path);
                $item->image_ratio = $image->getWidth() / $image->getHeight();
                $item->save();
                $count++;
                $this->output->progressAdvance();
            }
        });

        $this->output->progressFinish();

        $this->info('Generated image ratios for ' . $count . ' items.');
    }
}
