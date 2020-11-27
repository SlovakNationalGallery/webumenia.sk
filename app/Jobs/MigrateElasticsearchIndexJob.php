<?php

namespace App\Jobs;

use App\Elasticsearch\Repositories\TranslatableRepository;
use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
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
    protected $locales;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TranslatableRepository $repository, array $locales = null)
    {
        //TODO specify timeout of 30 mins
        $this->oldRepository = $repository;
        $this->locales = $locales ?? $this->oldRepository->getLocales();
        $this->elasticClient = app()->make(ElasticsearchClient::class);

        $newVersion = Carbon::now()->timestamp;
        $repositoryClass = get_class($this->oldRepository);
        $this->newRepository = new $repositoryClass($this->locales, $this->elasticClient, $newVersion);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Create new indices
        foreach ($this->locales as $locale) {
            $aliasName = $this->oldRepository->getLocalizedIndexName($locale);
            $newIndexName = $this->newRepository->getLocalizedIndexName($locale);

            Log::info("Creating {$newIndexName}");
            $this->elasticClient->indices()->create([
                'index' => $newIndexName,
                'body' => array_merge(
                    $this->newRepository->getIndexConfig($locale),
                    [
                        'mappings' => array_merge(
                            $this->newRepository->getMappingConfig($locale),
                        ),
                    ]
                )
            ]);
        }


        // Index into new index
        Log::info("Reindexing indices...");
        $this->newRepository->reindexAllLocales();

        foreach ($this->locales as $locale) {
            // Get old index name -- note this is different from the repository default (which is an alias)
            $oldIndexName = collect($this->elasticClient->indices()->getAlias(['name' => $aliasName]))
            ->keys()
            ->first();

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
