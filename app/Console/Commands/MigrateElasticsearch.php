<?php

namespace App\Console\Commands;

use App\Elasticsearch\Repositories\AuthorityRepository;
use App\Elasticsearch\Repositories\ItemRepository;
use App\Jobs\MigrateElasticsearchIndexJob;
use Illuminate\Console\Command;

class MigrateElasticsearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'es:migrate
                            {type : Index type(s) to migrate. Allowed values: items, authorities, all}
                            {--now : Run the re-index now (not as a queue job)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-creates, re-indexes and swaps aliases for Elasticsearch indexes. Processes all locales.';

    protected $availableTypes = ['items', 'authorities', 'all'];

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
        $selectedType = $this->argument('type');

        if (!in_array($selectedType, $this->availableTypes))
            $this->error("Unknown type '$selectedType'. Allowed values: " . join(', ', $this->availableTypes));

        $repositoryClasses = $this->getRepositoryClasses($selectedType);

        if ($this->option('now')) {
            foreach($repositoryClasses as $repositoryClass) {
                MigrateElasticsearchIndexJob::dispatchNow($repositoryClass, [$this, 'comment']);
            }
            return;
        }

        foreach($repositoryClasses as $repositoryClass) {
            MigrateElasticsearchIndexJob::dispatch($repositoryClass);
        }

        $this->comment('✅ Migration job(s) submitted to the queue.');
    }

    private function getRepositoryClasses($selectedType) {
        $repositoryClasses = [];

        if (in_array($selectedType, ['items', 'all'])) {
            $repositoryClasses[] = ItemRepository::class;
        }

        if (in_array($selectedType, ['authorities', 'all'])) {
            $repositoryClasses[] = AuthorityRepository::class;
        }

        return $repositoryClasses;
    }
}
