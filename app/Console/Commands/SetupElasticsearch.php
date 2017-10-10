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
    protected $signature = 'es:setup {lang=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Elasticsearch index and types with proper mapping for specified locale.';

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
      $client = new \GuzzleHttp\Client(['http_errors' => false]);

      $host = 'localhost:9200';
      $hosts = config('elasticsearch.hosts');
      if (!empty($hosts)) {
        $host = reset($hosts);
        $this->comment('Your ES host is: ' . $host);
      }

      switch ($this->argument('lang')) {
          case 'all':
            $this->setup_all_locales($client, $host);
            break;
          case 'sk':
            $this->setup_sk_locale($client, $host);
            break;
          case 'en':
            $this->setup_en_locale($client, $host);
            break;
          default:
              exit("Unknown language specified: {$this->argument('lang')}" );
      }

      // $this->test_function();

      $this->info("\nDone");
    }

    public function test_function()
    {
      $this->info('Test');
    }

    public function get_index_name($client, $host, $locale_str)
    {
      $default_index_name = 'webumenia_'.$locale_str;
      $index_name = $this->ask('What is the index name?', $default_index_name);


      $res = $client->head('http://'.$host.'/'.$index_name);

      if ($res->getStatusCode() == 200) {
          if ($this->confirm("An index with that name already exists.\n Do you want to delete the current index?\n [y|N]")) {
              $this->comment('Removing...');
              $res = $client->delete('http://'.$host.'/'.$index_name);
              echo $res->getBody() . "\n";
          }
      }

      return $index_name;
    }

    public function create_index($client, $host, $index_name, $index_params_str)
    {
      $this->comment('Creating index...');
      $res = $client->put('http://'.$host.'/'.$index_name, [
          'json' => json_decode($index_params_str, true),
      ]);
      echo $res->getBody() . "\n";

      if ($res->getStatusCode() == 200) {
          $this->info('Index ' . $index_name . ' was created');
      }
    }
    
    public function create_items($client, $host, $index_name, $items_params_str)
    {
      $this->comment('Creating type "items"...');
      $res = $client->put('http://'.$host.'/'.$index_name .'/_mapping/items', [
          'json' => json_decode($items_params_str, true),
      ]);
      echo $res->getBody() . "\n";

      if ($res->getStatusCode() == 200) {
          $this->info('Type "items" was created');
      }
    }

    public function create_authorities($client, $host, $index_name, $authorities_params_str)
    {
      $this->comment('Creating type "authorities"...');
      $res = $client->put('http://'.$host.'/'.$index_name .'/_mapping/authorities', [
          'json' => json_decode($authorities_params_str, true),
      ]);
      echo $res->getBody() . "\n";

      if ($res->getStatusCode() == 200) {
          $this->info('Type "authorities" was created');
      }
    }

    public function setup_all_locales($client, $host)
    {
      $this->comment('setting up ES index for all locales...');
      $this->setup_sk_locale($client, $host);
      $this->setup_en_locale($client, $host);
    }

    public function setup_sk_locale($client, $host)
    {
      $this->comment("\nsetting up ES index for locale: sk");
      $index_name = $this->get_index_name($client, $host, 'sk');

      $json_params_create_index_str       = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_index_sk.json');
      $json_params_create_items_str       = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_items_sk.json');
      $json_params_create_authorities_str = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_authorities_sk.json');

      $this->create_index($client, $host, $index_name, $json_params_create_index_str);
      $this->create_items($client, $host, $index_name, $json_params_create_items_str);
      $this->create_authorities($client, $host, $index_name, $json_params_create_authorities_str);      
    }

    public function setup_en_locale($client, $host)
    {
      $this->comment("\nsetting up ES index for locale: en");
      $index_name = $this->get_index_name($client, $host, 'en');

      $json_params_create_index_str       = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_index_en.json');
      $json_params_create_items_str       = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_items_en.json');
      $json_params_create_authorities_str = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_authorities_en.json');

      $this->create_index($client, $host, $index_name, $json_params_create_index_str);
      $this->create_items($client, $host, $index_name, $json_params_create_items_str);
      $this->create_authorities($client, $host, $index_name, $json_params_create_authorities_str);
    }
}
