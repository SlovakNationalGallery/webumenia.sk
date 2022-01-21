<?php

namespace App\Console\Commands;

use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Elasticsearch\Repositories\TranslatableRepository;
use Illuminate\Console\Command;

class SetupElasticsearch extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'es:setup';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Create as aliased Elasticsearch index and types with proper mapping for specified locale.';

    /** @var TranslatableRepository[] */
    protected $repositories;

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct(
        AuthorityRepository $authorityRepository,
        ItemRepository $itemRepository
    ) {
        parent::__construct();
        $this->repositories = [
            'authority' => $authorityRepository,
            'item' => $itemRepository
        ];
    }

    public function handle()
    {
        $locales = config('translatable.locales');
        $choices = array_merge(['all'], $locales);
        $selected_locale = $this->choice('Which locale(s) do you want to set up?', $choices, 0);
        $version = TranslatableRepository::buildNewVersionNumber();

        if ($selected_locale == 'all') {
            foreach ($locales as $locale) {
                $this->setup_for_locale($locale, $version);
            }
        } else {
            $this->setup_for_locale($selected_locale, $version);
        }

        $this->info("\nDone 🎉");
    }

    protected function setup_for_locale($locale, $version)
    {
        foreach ($this->repositories as $type => $repository) {
            $this->comment("\nSetting up $type index for locale: $locale");

            if ($repository->indexExists($locale)) {
                $index = $repository->getLocalizedIndexName($locale);

                if ($this->confirm("❗ An index with name $index already exists❗\n Do you want to delete the current index?\n [y|N]")) {
                    $this->comment('Removing...');

                    $repository->deleteIndex($locale);
                } else {
                    continue;
                }
            }

            $newRepository = $repository->buildWithVersion($version);
            $newRepository->createIndex($locale);
            $newRepository->createMapping($locale);
            $newRepository->createIndexAlias($locale);
        }
    }
}
