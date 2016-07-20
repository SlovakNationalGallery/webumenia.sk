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
    protected $description = 'Create Elasticsearch index and types with proper mapping.';

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
        $index_name = 'webumenia';
        $index_name = $this->ask('What is the index name?', $index_name);
        if (!$index_name) {
            $index_name = 'webumenia';
        }


        $res = $client->head('http://'.$host.'/'.$index_name);

        if ($res->getStatusCode() == 200) {
            if ($this->confirm('It already exist. Do you want to delete it? [y|N]')) {
                $this->comment('Removing...');
                $res = $client->delete('http://'.$host.'/'.$index_name);
                echo $res->getBody() . "\n";
            }
        } 

        // ********* INDEX

        $json_params_create_index = '
        {
          "settings": {
            "analysis": {
              "filter": {
                "autocomplete_filter": {
                  "type": "edge_ngram",
                  "min_gram": 2,
                  "max_gram": 20
                },
                "lemmagen_filter_sk": {
                  "type": "lemmagen",
                  "lexicon": "sk"
                },
                "synonym_filter": {
                  "type": "synonym",
                  "synonyms_path": "synonyms/sk_SK.txt",
                  "ignore_case": true
                },
                "stopwords_SK": {
                  "type": "stop",
                  "stopwords_path": "stop-words/stop-words-slovak.txt",
                  "ignore_case": true
                }
              },
              "analyzer": {
                "slovencina_synonym": {
                  "type": "custom",
                  "tokenizer": "standard",
                  "filter": [
                    "stopwords_SK",
                    "lemmagen_filter_sk",
                    "lowercase",
                    "stopwords_SK",
                    "synonym_filter",
                    "asciifolding"
                  ]
                },
                "slovencina": {
                  "type": "custom",
                  "tokenizer": "standard",
                  "filter": [
                    "stopwords_SK",
                    "lemmagen_filter_sk",
                    "lowercase",
                    "stopwords_SK",
                    "asciifolding"
                  ]
                },
                "autocomplete": {
                  "type": "custom",
                  "tokenizer": "standard",
                  "filter": [
                    "lowercase",
                    "asciifolding",
                    "autocomplete_filter"
                  ]
                },
                "ascii_folding": {
                  "type": "custom",
                  "tokenizer": "standard",
                  "filter": [
                    "lowercase",
                    "asciifolding"
                  ]
                }
              }
            }
          }
        }
        ';

        $this->comment('Creating...');
        $res = $client->put('http://'.$host.'/'.$index_name, [
            'json' => json_decode($json_params_create_index, true),
        ]);
        echo $res->getBody() . "\n";

        if ($res->getStatusCode() == 200) {
            $this->info('Index ' . $index_name . ' was created');
        }

        // ********* ITEMS

        $json_params_create_items = '
        {
          "items": {
            "properties": {
              "id": {
                "type": "string",
                "index": "not_analyzed"
              },
              "identifier": {
                "type": "string",
                "index": "not_analyzed"
              },
              "author": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  },
                  "stemmed": { 
                    "type":     "string",
                    "analyzer": "slovencina"
                  },
                  "suggest": { 
                    "type":     "string",
                    "analyzer": "autocomplete",
                    "search_analyzer": "ascii_folding"
                  }
                }
              },
              "title": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": { 
                    "type":     "string",
                    "analyzer": "ascii_folding"
                  },
                  "stemmed": { 
                    "type":     "string",
                    "analyzer": "slovencina"
                  },
                  "suggest": { 
                    "type":     "string",
                    "analyzer": "autocomplete",
                    "search_analyzer": "ascii_folding"
                  }
                }
              },
              "description": {
                "type": "string",
                "analyzer": "ascii_folding",
                "fields": {
                  "stemmed": { 
                    "type":     "string",
                    "analyzer": "slovencina"
                  }
                }
              },
              "topic": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  }
                }
              },
              "technique": {
                "index": "not_analyzed",
                "type": "string"
              },
              "dating": {
                "type": "string"
              },
              "date_earliest": {
                "type": "date",
                "index": "not_analyzed",
                "format" : "yyyy"
              },
              "date_latest": {
                "type": "date",
                "index": "not_analyzed",
                "format" : "yyyy"
              },
              "gallery": {
                "type": "string",
                "index": "not_analyzed"
              },
              "tag": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  },
                  "stemmed": { 
                    "type":     "string",
                    "analyzer": "slovencina"
                  }
                }
              },
              "work_type": {
                "type": "string",
                "index": "not_analyzed"
              },
              "related_work": {
                "type": "string",
                "index": "not_analyzed"
              },
              "view_count": {
                "type": "integer",
                "index": "not_analyzed"
              },
              "place": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  }
                }
              },
              "medium": {
                "type": "string",
                "index": "not_analyzed"
              },
              "created_at" : {
                "type": "date",
                "index": "not_analyzed",
                "format" : "yyyy-MM-dd HH:mm:ss"
              },
              "updated_at" : {
                "type": "date",
                "index": "not_analyzed",
                "format" : "yyyy-MM-dd HH:mm:ss"
              },
              "has_image" : {
                "type": "boolean"
              },
              "has_iip" : {
                "type": "boolean"
              },
              "is_free" : {
                "type": "boolean"
              },
              "free_download" : {
                "type": "boolean"
              },
              "authority_id" : {
                "type": "string",
                "index": "not_analyzed"
              }
            }
          }
        }
        ';

        $this->comment('Creating type "items"...');
        $res = $client->put('http://'.$host.'/'.$index_name .'/_mapping/items', [
            'json' => json_decode($json_params_create_items, true),
        ]);
        echo $res->getBody() . "\n";

        if ($res->getStatusCode() == 200) {
            $this->info('Type "items" was created');
        }

        // ********* AUTHORITIES

        $json_params_create_authorities = '
        {
          "authorities": {
            "properties": {
              "id": {
                "type": "string",
                "index": "not_analyzed"
              },
              "identifier": {
                "type": "string",
                "index": "not_analyzed"
              },
              "type": {
                "type": "string",
                "index": "not_analyzed"
              },
              "name": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  },
                  "suggest": { 
                    "type":     "string",
                    "analyzer": "autocomplete",
                    "search_analyzer": "ascii_folding"
                  }
                }
              },
              "alternative_name": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  },
                  "suggest": { 
                    "type":     "string",
                    "analyzer": "autocomplete",
                    "search_analyzer": "ascii_folding"
                  }
                }
              },
              "related_name": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  },
                  "suggest": { 
                    "type":     "string",
                    "analyzer": "autocomplete",
                    "search_analyzer": "ascii_folding"
                  }
                }
              },
              "biography": {
                "type": "string",
                "analyzer": "ascii_folding",
                "fields": {
                  "stemmed": { 
                    "type":     "string",
                    "analyzer": "slovencina"
                  }
                }
              },
              "nationality": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  }
                }
              },
              "place": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  }
                }
              },
              "role": {
                "type": "string",
                "index": "not_analyzed",
                "fields": {
                  "folded": {
                    "type": "string",
                    "analyzer": "ascii_folding"
                  }
                }
              },
              "birth_year": {
                "type": "date",
                "index": "not_analyzed",
                "format" : "yyyy"
              },
              "death_year": {
                "type": "date",
                "index": "not_analyzed",
                "format" : "yyyy"
              },
              "birth_place": {
                "type": "string",
                "index": "not_analyzed"
              },
              "death_place": {
                "type": "string",
                "index": "not_analyzed"
              },
              "sex": {
                "type": "string",
                "index": "not_analyzed"
              },
              "has_image": {
                "type": "boolean"
              },
              "items_count": {
                "type": "integer"
              },
              "items_with_images_count": {
                "type": "integer"
              },
              "created_at" : {
                "type": "date",
                "index": "not_analyzed",
                "format" : "yyyy-MM-dd HH:mm:ss"
              }
            }
          }
        }
        ';

        $this->comment('Creating type "authorities"...');
        $res = $client->put('http://'.$host.'/'.$index_name .'/_mapping/authorities', [
            'json' => json_decode($json_params_create_authorities, true),
        ]);
        echo $res->getBody() . "\n";

        if ($res->getStatusCode() == 200) {
            $this->info('Type "authorities" was created');
        }

        $this->info('Done');




    }
}
