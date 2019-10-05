<?php

namespace App\Console\Commands;

use App\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;

class ItemsExtractColors extends Command
{
    protected $signature = 'items:extract-colors '
                         . '{--all : force description of already processed items} '
                         . '{i? : i-th cluster of items that will be processed} '
                         . '{n? : number of clusters of items}';

    protected $description = 'Extract item colors.';

    public function handle(ColorExtractor $extractor)
    {
        $items = Item::query();

        if (!$this->option('all')) {
            $items->whereNull('colors');
        }

        $n = (int)$this->argument('n');
        $i = (int)$this->argument('i');

        if ($this->argument('n') === null && $this->argument('i') === null) {
            // go ahead
        } else if ($this->argument('n') === null || $this->argument('i') === null) {
            $this->error("You must specify either none or both 'n' and 'i' arguments");
            return null;
        } else if ($i > $n || $i <= 0) {
            $this->error("Arguemnt 'i' must be less than 'n' and both have to be positive integers");
            return null;
        } else {
            $items->where(DB::raw("created_at MOD $n"), '=', $i - 1);
        }

        $progressBar = $this->output->createProgressBar($items->count());
        $items->chunk(100, function ($chunked) use ($extractor, $progressBar) {
            foreach ($chunked as $item) {
                $progressBar->advance();

                $filename = $item->getImagePath($full = true);

                if (!is_file($filename)) {
                    Log::warning(sprintf("File '%s' does not exist", $filename));
                    continue;
                }

                if (!@getimagesize($filename)) {
                    Log::warning(sprintf("File '%s' is not valid image", $filename));
                    continue;
                }

                try {
                    $colors = $extractor->extract($filename, config('items.colors.count'));
                } catch (\Exception $e) {
                    Log::warning(sprintf('%s: %s', $filename, $e->getMessage()));
                    continue;
                }

                $colors = collect($colors)
                    ->mapWithKeys(function ($amount, $int) {
                        return [Color::fromIntToHex($int) => $amount];
                    })
                    ->sort()
                    ->reverse();

                $item->colors = $colors;
                $item->save();
            }
        });

        $progressBar->finish();
    }
}