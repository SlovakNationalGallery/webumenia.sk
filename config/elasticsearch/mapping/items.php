<?php

$mapping = [
    'properties' => [
        'id' => [
            'type' => 'keyword',
        ],
        'identifier' => [
            'type' => 'keyword',
        ],
        'author' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'ascii_folding',
                ],
                'stemmed' => [
                    'type' => 'text',
                    'analyzer' => 'english',
                ],
                'suggest' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete',
                    'search_analyzer' => 'ascii_folding',
                ]
            ]
        ],
        'title' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'ascii_folding',
                ],
                'stemmed' => [
                    'type' => 'text',
                    'analyzer' => 'english',
                ],
                'suggest' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete',
                    'search_analyzer' => 'ascii_folding',
                ]
            ]
        ],
        'description' => [
            'type' => 'text',
            'analyzer' => 'ascii_folding',
            'fields' => [
                'stemmed' => [
                    'type' => 'text',
                    'analyzer' => 'english',
                ]
            ]
        ],
        'topic' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'ascii_folding',
                ]
            ]
        ],
        'technique' => [
            'type' => 'keyword',
        ],
        'dating' => [
            'type' => 'text',
        ],
        'date_earliest' => [
            'type' => 'integer',
        ],
        'date_latest' => [
            'type' => 'integer',
        ],
        'gallery' => [
            'type' => 'keyword',
        ],
        'tag' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'ascii_folding',
                ],
                'stemmed' => [
                    'type' => 'text',
                    'analyzer' => 'english',
                ]
            ]
        ],
        'work_type' => [
            'type' => 'keyword',
        ],
        'related_work' => [
            'type' => 'keyword',
        ],
        'view_count' => [
            'type' => 'keyword',
        ],
        'place' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'ascii_folding'
                ]
            ]
        ],
        'medium' => [
            'type' => 'keyword',
        ],
        'created_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss',
        ],
        'updated_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss',
        ],
        'has_image' => [
            'type' => 'boolean',
        ],
        'has_iip' => [
            'type' => 'boolean',
        ],
        'is_free' => [
            'type' => 'boolean',
        ],
        'free_download' => [
            'type' => 'boolean',
        ],
        'authority_id' => [
            'type' => 'keyword',
        ],
    ],
];

return [
    'en' => $mapping,
    'sk' => $mapping,
    'cs' => $mapping,
];