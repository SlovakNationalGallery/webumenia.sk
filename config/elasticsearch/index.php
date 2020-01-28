<?php

$base_filters = [
    'autocomplete_filter' => [
        'type' => 'edge_ngram',
        'min_gram' => 2,
        'max_gram' => 20,
    ]
];

$en_filters = $base_filters + [
    'synonyms_filter' => [
        'type' => 'synonym',
        'format' => 'wordnet',
        'synonyms_path' => 'synonyms/wn_s.pl',
    ],
    'stopwords_filter' => [
        'type' => 'stop',
        'stopwords' => '_english_',
    ],
    'stemmer_filter' => [
        'type' => 'stemmer',
        'language' => 'english',
    ],
    'possessive_stemmer_filter' => [
        'type' => 'stemmer',
        'language' => 'possessive_english',
    ]
];

$sk_filters = $base_filters + [
    'lemmagen_filter' => [
        'type' => 'lemmagen',
        'lexicon' => 'sk',
    ],
    'synonyms_filter' => [
        'type' => 'synonym',
        'synonyms_path' => 'synonyms/sk_SK.txt',
    ],
    'stopwords_filter' => [
        'type' => 'stop',
        'stopwords_path' => 'stop-words/stop-words-slovak.txt',
    ],
];

$cs_filters = $base_filters + [
    'lemmagen_filter' => [
        'type' => 'lemmagen',
        'lexicon' => 'cs',
    ],
    'synonyms_filter' => [
        'type' => 'synonym',
        'synonyms_path' => 'synonyms/synonyms_cz.txt',
    ],
    'stopwords_filter' => [
        'type' => 'stop',
        'stopwords_path' => 'stop-words/stop-words-czech2.txt',
    ],
];

$base_analyzers = [
    'autocomplete_analyzer' => [
        'type' => 'custom',
        'tokenizer' => 'standard',
        'filter' => [
            'lowercase',
            'asciifolding',
            'autocomplete_filter'
        ],
    ],
    'asciifolding_analyzer' => [
        'type' => 'custom',
        'tokenizer' => 'standard',
        'filter' => [
            'lowercase',
            'asciifolding',
        ],
    ],
];

$en_analyzers = $base_analyzers + [
    'default_analyzer' => [
        'tokenizer' => 'standard',
        'filter' => [
            'possessive_stemmer_filter',
            'lowercase',
            'stopwords_filter',
            'stemmer_filter',
        ],
    ],
    'synonyms_analyzer' => [
        'tokenizer' => 'standard',
        'filter' => [
            'possessive_stemmer_filter',
            'lowercase',
            'synonyms_filter',
            'stopwords_filter',
            'stemmer_filter',
        ],
    ],
];

$cs_analyzers = $sk_analyzers = $base_analyzers + [
    'default_analyzer' => [
        'type' => 'custom',
        'tokenizer' => 'standard',
        'filter' => [
            'lemmagen_filter',
            'lowercase',
            'stopwords_filter',
            'asciifolding',
        ],
    ],
    'synonyms_analyzer' => [
        'type' => 'custom',
        'tokenizer' => 'standard',
        'filter' => [
            'lemmagen_filter',
            'lowercase',
            'synonyms_filter',
            'stopwords_filter',
            'asciifolding',
        ],
    ],
];

$get_settings = function (array $filters, array $analyzers) {
    return [
        'settings' => [
            'analysis' => [
                'filter' => $filters,
                'analyzer' => $analyzers,
            ],
        ],
    ];
};

$common = [
    'en' => $get_settings($en_filters, $en_analyzers),
    'sk' => $get_settings($sk_filters, $sk_analyzers),
    'cs' => $get_settings($cs_filters, $cs_analyzers),
];

return [
    'items' => $common,
    'authorities' => $common,
];
