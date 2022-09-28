<?php

$mapping = [
    'properties' => [
        'alternative_name' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer'
                ],
                'suggest' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'asciifolding_analyzer'
                ]
            ]
        ],
        'biography' => [
            'type' => 'text',
            'analyzer' => 'asciifolding_analyzer',
            'fields' => [
                'stemmed' => [
                    'type' => 'text',
                    'analyzer' => 'default_analyzer'
                ]
            ]
        ],
        'birth_place' => [
            'type' => 'keyword',
        ],
        'birth_year' => [
            'type' => 'integer',
        ],
        'created_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ],
        'death_place' => [
            'type' => 'keyword',
        ],
        'death_year' => [
            'type' => 'integer',
        ],
        'has_image' => [
            'type' => 'boolean'
        ],
        'id' => [
            'type' => 'keyword',
        ],
        'identifier' => [
            'type' => 'keyword',
        ],
        'items_count' => [
            'type' => 'integer'
        ],
        'items_with_images_count' => [
            'type' => 'integer'
        ],
        'name' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer'
                ],
                'suggest' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'asciifolding_analyzer'
                ]
            ]
        ],
        'nationality' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer'
                ]
            ]
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
        'related_name' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer'
                ],
                'suggest' => [
                    'type' => 'text',
                    'analyzer' => 'autocomplete_analyzer',
                    'search_analyzer' => 'asciifolding_analyzer'
                ]
            ]
        ],
        'role' => [
            'type' => 'keyword',
            'fields' => [
                'folded' => [
                    'type' => 'text',
                    'analyzer' => 'asciifolding_analyzer'
                ]
            ]
        ],
        'sex' => [
            'type' => 'keyword',
        ],
        'type' => [
            'type' => 'keyword',
        ],
    ]
];

return [
    'en' => $mapping,
    'sk' => $mapping,
    'cs' => $mapping,
];