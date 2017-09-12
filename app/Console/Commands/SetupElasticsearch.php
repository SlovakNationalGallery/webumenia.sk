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
    protected $signature = 'es:setup {lang=sk}';

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
        $this->comment('setting up ES index for locale '.$this->argument('lang'));

        $client = new \GuzzleHttp\Client(['http_errors' => false]);

        $host = 'localhost:9200';
        $hosts = config('elasticsearch.hosts');
        if (!empty($hosts)) {
          $host = reset($hosts);
          $this->comment('Your ES host is: ' . $host);
        }

        $index_name = 'webumenia_'.$this->argument('lang');
        $index_name = $this->ask('What is the index name?', $index_name);


        $res = $client->head('http://'.$host.'/'.$index_name);

        if ($res->getStatusCode() == 200) {
            if ($this->confirm("An index with that name already exists.\n Do you want to delete the current index?\n [y|N]")) {
                $this->comment('Removing...');
                $res = $client->delete('http://'.$host.'/'.$index_name);
                echo $res->getBody() . "\n";
            }
        }         

        switch ($this->argument('lang')) {
            case 'sk':
                $json_params_create_index_str       = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_index_sk.json');
                $json_params_create_items_str       = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_items_sk.json');
                $json_params_create_authorities_str = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_authorities_sk.json');
                break;
            case 'en':
                $json_params_create_index_str       = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_index_en.json');
                $json_params_create_items_str       = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_items_en.json');
                $json_params_create_authorities_str = file_get_contents('app/Console/Commands/SetupElasticsearch/json_params_create_authorities_en.json');
                break;
            default:
                exit("Unknown language specified: {$this->argument('lang')}" );
        }

        // ********* INDEX

        $this->comment('Creating index...');
        $res = $client->put('http://'.$host.'/'.$index_name, [
            'json' => json_decode($json_params_create_index_str, true),
        ]);
        echo $res->getBody() . "\n";

        if ($res->getStatusCode() == 200) {
            $this->info('Index ' . $index_name . ' was created');
        }
      
        // ********* ITEMS
        
        $this->comment('Creating type "items"...');
        $res = $client->put('http://'.$host.'/'.$index_name .'/_mapping/items', [
            'json' => json_decode($json_params_create_items_str, true),
        ]);
        echo $res->getBody() . "\n";

        if ($res->getStatusCode() == 200) {
            $this->info('Type "items" was created');
        }

        // ********* AUTHORITIES

        $this->comment('Creating type "authorities"...');
        $res = $client->put('http://'.$host.'/'.$index_name .'/_mapping/authorities', [
            'json' => json_decode($json_params_create_authorities_str, true),
        ]);
        echo $res->getBody() . "\n";

        if ($res->getStatusCode() == 200) {
            $this->info('Type "authorities" was created');
        }


        $this->info('Done');
    }
}
