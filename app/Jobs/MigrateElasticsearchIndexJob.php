<?php

namespace App\Jobs;

use App\Elasticsearch\Repositories\TranslatableRepository;
use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class MigrateElasticsearchIndexJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var TranslatableRepository */
    protected $oldRepository;
    /** @var TranslatableRepository */
    protected $newRepository;
    /** @var ElasticsearchClient */
    protected $elasticClient;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TranslatableRepository $repository)
    {
        $this->oldRepository = $repository;
        $this->elasticClient = app()->make(ElasticsearchClient::class);

        $newVersion = TranslatableRepository::buildNewVersionNumber();
        $this->newRepository = $this->oldRepository->buildWithVersion($newVersion);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $locales = $this->oldRepository->locales;

        // Create new indices
        foreach ($locales as $locale) {
            $aliasName = $this->newRepository->getIndexAliasName($locale);
            $newIndexName = $this->newRepository->getVersionedIndexName($locale);

            Log::info("Creating {$newIndexName}");
            $this->newRepository->createIndex($locale);
            $this->newRepository->createMapping($locale);
        }

        // Index into new index
        Log::info("Reindexing indices...");
        $this->newRepository->reindexAllLocales();

        foreach ($locales as $locale) {
            $oldIndexName = $this->oldRepository->fetchVersionedIndexName($locale);
            $newIndexName = $this->newRepository->getVersionedIndexName($locale);
            $aliasName = $this->newRepository->getIndexAliasName($locale);

            // Replace aliases
            Log::info("Moving alias {$aliasName} from index {$oldIndexName} to {$newIndexName}");
            $this->elasticClient->indices()->updateAliases([
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
            Log::info("Deleting old index {$oldIndexName}");
            $this->elasticClient->indices()->delete([
                'index' => $oldIndexName
            ]);
        }
    }
}
