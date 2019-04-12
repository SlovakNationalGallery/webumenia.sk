<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCuratorInEsIndex extends Migration
{
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['http_errors' => false]);
        $this->host = 'http://localhost:9200';
        $hosts = config('elasticsearch.hosts');
        if (!empty($hosts)) {
            $this->host = reset($hosts);
        };
        $this->index_name = "web_umenia_sk,web_umenia_cs,web_umenia_en";
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $mapping_name = "items";
        $mapping_params_str = 
        "{
            'properties': {
                'contributor': {
                    'index': 'not_analyzed',
                    'type': 'string'
                }
            }
        }";
        $res = $this->client->put($this->host.'/'.$this->index_name.'/_mapping/'.$mapping_name, [
            'json' => json_decode($mapping_params_str, true),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
