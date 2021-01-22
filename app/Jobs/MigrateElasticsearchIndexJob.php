<?php

namespace App\Jobs;

use App\Elasticsearch\Repositories\TranslatableRepository;
use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigrateElasticsearchIndexJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string */
    private $repositoryClass;

    /** @var callable */
    private $logMethod;

    /** @var string */
    private $newVersion;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $repositoryClass, callable $logMethod = null)
    {
        $this->repositoryClass = $repositoryClass;
        $this->logMethod = $logMethod?? 'Log::info';
        $this->newVersion = TranslatableRepository::buildNewVersionNumber();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oldRepository = app()->make($this->repositoryClass);
        $newRepository = $oldRepository->buildWithVersion($this->newVersion);
        $elasticClient = app()->make(ElasticsearchClient::class);
        $locales = $oldRepository->locales;

        // Create new indices
        foreach ($locales as $locale) {
            $aliasName = $newRepository->getIndexAliasName($locale);
            $newIndexName = $newRepository->getVersionedIndexName($locale);

            $this->log("Creating {$newIndexName}");
            $newRepository->createIndex($locale);
            $newRepository->createMapping($locale);
        }

        // Index into new index
        $this->log("Reindexing -- this may take a while...");
        $newRepository->reindexAllLocales();

        foreach ($locales as $locale) {
            $oldIndexName = $oldRepository->fetchVersionedIndexName($locale);
            $newIndexName = $newRepository->getVersionedIndexName($locale);
            $aliasName = $newRepository->getIndexAliasName($locale);

            // Replace aliases
            $this->log("Moving alias {$aliasName}: {$oldIndexName} -> {$newIndexName}");
            $elasticClient->indices()->updateAliases([
                'body' => [
                    'actions' => [
                        [
                            'remove' => [
                                'index' => $oldIndexName,
                                'alias' => $aliasName,
                            ],
                        ],
                        [
                            'add' => [
                                'index' => $newIndexName,
                                'alias' => $aliasName,
                            ]
                        ]
                    ]
                ]
            ]);

            // Drop old index
            $this->log("Deleting old index {$oldIndexName}");
            $elasticClient->indices()->delete([
                'index' => $oldIndexName
            ]);
        }
    }

    private function log($message)
    {
        call_user_func($this->logMethod, $message);
    }
}
