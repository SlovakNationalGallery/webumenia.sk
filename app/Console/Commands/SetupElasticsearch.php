<?php

namespace App\Console\Commands;

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
  protected $description = 'Create Elasticsearch index and types with proper mapping for specified locale.';

  const CONFIG_PATH = 'SetupElasticsearch';

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
    $base_path = resource_path(self::CONFIG_PATH);

    $client = new \GuzzleHttp\Client(['http_errors' => false]);

    $host = 'http://localhost:9200';
    $hosts = config('elasticsearch.hosts');
    if (!empty($hosts)) {
      $host = reset($hosts);
      $this->comment('Your ES host is: ' . $host);
    }

    // get available locales based on directory names
    $available_es_locales = array_map('basename', glob($base_path ."/*", GLOB_ONLYDIR));

    $missing_es_locales = array_diff(config('translatable.locales'), $available_es_locales);
    // dd($missing_es_locales);

    // check if we have defined ES schema for every enabled locale for translatable models
    if (!empty($missing_es_locales)) {
      $this->error('ES setup configuration is missing for the following localizations: [' . implode(', ', $missing_es_locales). ']');
      $this->error('Please add ES setup configuration or disable them in the application');

      return;
    }

    // let user select from available locales
    $locale_options = array_merge(['all'], $available_es_locales);
    $selected_locale = $this->choice('Which locale(s) do you want to set up?', $locale_options, 0);

    if ($selected_locale == 'all') {
      foreach ($available_es_locales as $key => $locale) {
        $this->setup_for_locale($client, $host, $base_path, $locale);
      }
    }
    else {
      $this->setup_for_locale($client, $host, $base_path, $selected_locale);
    }

    $this->info("\nDone 🎉");
  }

  public function setup_for_locale($client, $host, $base_path, $locale_str)
  {
    $this->comment("\nsetting up ES index for locale: $locale_str");
    $index_name = $this->get_index_name($client, $host, $locale_str);

    $json_params_create_index_str       = file_get_contents("$base_path/$locale_str/index.json");
    $json_params_create_mapping_str     = file_get_contents("$base_path/$locale_str/mapping.json");

    $this->create_index($client, $host, $index_name, $json_params_create_index_str);
    $this->create_mapping($client, $host, $index_name, $json_params_create_mapping_str);
  }

  public function get_index_name($client, $host, $locale_str)
  {
    $elastic_translatable = \App::make('ElasticTranslatableService');

    $index_name = $this->ask('What is the index name?', $elastic_translatable->getIndexForLocale($locale_str));

    $res = $client->get($host.'/'.$index_name);

    if ($res->getStatusCode() == 200) {
        if ($this->confirm("❗ An index with that name already exists❗\n Do you want to delete the current index?\n [y|N]")) {
            $this->comment('Removing...');
            $res = $client->delete($host.'/'.$index_name);
            echo $res->getBody() . "\n";
        }
    }

    return $index_name;
  }

  public function create_index($client, $host, $index_name, $index_params_str)
  {
    $this->comment('Creating index...');
    $res = $client->put($host.'/'.$index_name, [
        'json' => json_decode($index_params_str, true),
    ]);
    echo $res->getBody() . "\n";

    if ($res->getStatusCode() == 200) {
        $this->info('Index ' . $index_name . ' was created');
    }
  }

  public function create_mapping($client, $host, $index_name, $mapping_params_str)
  {
    $this->comment("Creating mapping...");
    $res = $client->put($host.'/'.$index_name.'/_mapping/', [
        'json' => json_decode($mapping_params_str, true),
    ]);
    echo $res->getBody() . "\n";

    if ($res->getStatusCode() == 200) {
        $this->info("Mapping was created");
    }
  }
}