<?php

namespace App\Console\Commands;

use App\Descriptors\ColorDescriptor;
use App\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DescribeItemColors extends Command
{
    protected $signature = 'color_descriptors:create {--all} {i?} {n?}';

    protected $description = 'Create color descriptors.';

    protected $descriptor;

    public function __construct(ColorDescriptor $descriptor) {
        parent::__construct();
        $this->descriptor = $descriptor;
    }

    public function fire()
    {
        $items = Item::query();

        if (!$this->option('all')) {
            $items->whereNull('color_descriptor');
        }

        $n = (int)$this->argument('n');
        $i = (int)$this->argument('i');

        if ($this->argument('n') === null && $this->argument('i') === null) {
            // go ahead
        } else if ($this->argument('n') === null || $this->argument('i') === null) {
            return $this->error("You must specify either none or both 'n' and 'i' arguments");
        } else if ($i > $n || $i <= 0) {
            return $this->error("Arguemnt 'i' must be less than 'n' and both have to be positive integers");
        } else {
            $items->where(DB::raw("created_at MOD $n"), '=', $i - 1);
        }

        $items->chunk(100, function ($chunked) {
            foreach ($chunked as $item) {
                echo $item->id . PHP_EOL;
                $filename = $item->getImagePath($full = true);

                if (!is_file($filename)) {
                    Log::warning(sprintf("File '%s' does not exist", $filename));
                    continue;
                }

                if (!@getimagesize($filename)) {
                    Log::warning(sprintf("File '%s' is not valid image", $filename));
                    continue;
                }

                $item->color_descriptor = $this->descriptor->describe($filename);

                $item->save();
            }
        });
    }
}