<?php

namespace App\Console\Commands;

use App\Authority;
use App\Item;
use App\Matchers\AuthorityMatcher;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

class MatchAuthorities extends Command
{
    protected $authorityMatcher;

    protected $name = 'authorities:match';

    protected $description = 'Match existing item authors with authorities';

    public function __construct(AuthorityMatcher $authorityMatcher)
    {
        parent::__construct();
        $this->authorityMatcher = $authorityMatcher;
    }

    public function handle()
    {
        $progressBar = $this->output->createProgressBar(Item::count());
        $new = 0;

        Item::with('authorities')->chunk(100, function ($items) use ($progressBar, $new) {
            foreach ($items as $item) {
                $ids = $this->authorityMatcher
                    ->matchAll($item)
                    ->filter(function (Authority $authority) use ($item) {
                        return !$item->authorities->contains($authority);
                    })
                    ->map(function (Authority $authority) {
                        return $authority->id;
                    });

                $item->authorities()->syncWithoutDetaching($ids);

                $new += count($ids);
                $progressBar->advance();
            }
        });

        $this->output->writeln(sprintf('%d new relations were created', $new));
        $progressBar->finish();
    }
}