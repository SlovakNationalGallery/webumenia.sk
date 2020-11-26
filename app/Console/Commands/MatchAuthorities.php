<?php

namespace App\Console\Commands;

use App\Item;
use App\Matchers\AuthorityMatcher;
use Illuminate\Console\Command;

class MatchAuthorities extends Command
{
    protected $authorityMatcher;

    protected $signature = 'authorities:match
                            {-o|--output= : Log file of unsuccessful matches (defaults to stdout)}';

    protected $description = 'Match existing item authors with authorities';

    public function __construct(AuthorityMatcher $authorityMatcher)
    {
        parent::__construct();
        $this->authorityMatcher = $authorityMatcher;
    }

    public function handle()
    {
        $output = $this->option('output');
        $fp = $output !== null ? fopen($output, 'w+') : STDOUT;

        $progressBar = $this->output->createProgressBar(Item::count());
        $count = 0;

        Item::with('authorities')->chunk(100, function ($items) use ($progressBar, $fp, &$count) {
            foreach ($items as $item) {
                $ids = $this->authorityMatcher
                    ->matchAll($item)
                    ->map(function ($authorities, $author) use ($fp) {
                        if ($authorities->count() === 0) {
                            $message = sprintf('No authority matched (%s)', $author);
                        } else if ($authorities->count() === 1) {
                            return $authorities->first()->id;
                        } else {
                            $multiple = $authorities->pluck('id')->implode(', ');
                            $message = sprintf('Multiple authorities matched (%s)', $multiple);
                        }

                        fwrite($fp, $message . PHP_EOL);
                        return null;
                    })
                    ->filter();

                $changes = $item->authorities()->syncWithoutDetaching($ids);
                $item->authorities()->updateExistingPivot($changes['attached'], ['automatically_matched' => true]);

                $count += count($changes['attached']);
                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->output->writeln(sprintf("\n%d new relations were created", $count));

        if ($output !== null) {
            fclose($fp);
        }
    }
}