<?php

$mapping = [
    'properties' => [
        'author' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer',
                ],
                'stemmed' => [
                    'type' => 'text',
                    'analyzer' => 'default_analyzer',
                ],
                'suggest' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'asciifolding_analyzer',
                ]
            ]
        ],
        'authority_id' => [
            'type' => 'keyword',
        ],
        'color_descriptor' => [
            'type' => 'float'
        ],
        'created_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss',
        ],
        'date_earliest' => [
            'type' => 'integer',
        ],
        'date_latest' => [
            'type' => 'integer',
        ],
        'dating' => [
            'type' => 'text',
        ],
        'description' => [
            'type' => 'text',
            'analyzer' => 'asciifolding_analyzer',
            'fields' => [
                'stemmed' => [
                    'type' => 'text',
                    'analyzer' => 'default_analyzer',
                ]
            ]
        ],
        'free_download' => [
            'type' => 'boolean',
        ],
        'gallery' => [
            'type' => 'keyword',
        ],
        'credit' => [
            'type' => 'keyword',
        ],
        'has_iip' => [
            'type' => 'boolean',
        ],
        'has_image' => [
            'type' => 'boolean',
        ],
        'id' => [
            'type' => 'keyword',
        ],
        'identifier' => [
            'type' => 'keyword',
        ],
        'is_free' => [
            'type' => 'boolean',
        ],
        'medium' => [
            'type' => 'keyword',
        ],
        'place' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer'
                ]
            ]
        ],
        'related_work' => [
            'type' => 'keyword',
        ],
        'tag' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer',
                ],
                'stemmed' => [
                    'type' => 'text',
                    'analyzer' => 'default_analyzer',
                ]
            ]
        ],
        'technique' => [
            'type' => 'keyword',
        ],
        'title' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer',
                ],
                'stemmed' => [
                    'type' => 'text',
                    'analyzer' => 'default_analyzer',
                ],
                'suggest' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'asciifolding_analyzer',
                ]
            ]
        ],
        'topic' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer',
                ]
            ]
        ],
        'updated_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss',
        ],
        'work_type' => [
            'type' => 'text',
            'analyzer' => 'tree_analyzer',
            'fielddata' => true,
        ],
        'view_count' => [
            'type' => 'integer',
        ],
        'hsl' => [
            'type' => 'nested',
        ],
        'additionals' => [
            'type' => 'object'
        ],
        'images' => [
            'type' => 'keyword'
        ],
    ],
];

return [
    'en' => $mapping,
    'sk' => $mapping,
    'cs' => $mapping,
];