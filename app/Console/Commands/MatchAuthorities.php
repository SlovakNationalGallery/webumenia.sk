<?php

namespace App\Console\Commands;

use App\Item;
use App\Matchers\AuthorityMatcher;
use Illuminate\Console\Command;

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
        $attached = 0;

        Item::with('authorities')->chunk(100, function ($items) use ($progressBar, $attached) {
            foreach ($items as $item) {
                $ids = $this->authorityMatcher
                    ->matchAll($item)
                    ->map(function ($authorities, $author) {
                        if (count($authorities) === 1) {
                            return $authorities[0]->id;
                        }

                        if (count($authorities) === 0) {
                            $this->output->writeln(sprintf('No authority matched (%s)', $author));
                        } else {
                            $multiple = $authorities->pluck('id')->implode(', ');
                            $this->output->writeln(sprintf('Multiple authorities matched (%s)', $multiple));
                        }

                        return null;
                    });

                $changes = $item->authorities()->syncWithoutDetaching($ids);
                $attached += count($changes['attached']);
                $progressBar->advance();
            }
        });

        $this->output->writeln(sprintf('%d new relations were created', $attached));
        $progressBar->finish();
    }
}