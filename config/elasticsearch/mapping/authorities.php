<?php

$mapping = [
    "properties" => [
        "id" => [
            "type" => "keyword",
        ],
        "identifier" => [
            "type" => "keyword",
        ],
        "type" => [
            "type" => "keyword",
        ],
        "name" => [
            "type" => "keyword",
            "fields" => [
                "folded" => [
                    "type" => "text",
                    "analyzer" => "ascii_folding"
                ],
                "suggest" => [
                    "type" => "text",
                    "analyzer" => "autocomplete",
                    "search_analyzer" => "ascii_folding"
                ]
            ]
        ],
        "alternative_name" => [
            "type" => "keyword",
            "fields" => [
                "folded" => [
                    "type" => "text",
                    "analyzer" => "ascii_folding"
                ],
                "suggest" => [
                    "type" => "text",
                    "analyzer" => "autocomplete",
                    "search_analyzer" => "ascii_folding"
                ]
            ]
        ],
        "related_name" => [
            "type" => "keyword",
            "fields" => [
                "folded" => [
                    "type" => "text",
                    "analyzer" => "ascii_folding"
                ],
                "suggest" => [
                    "type" => "text",
                    "analyzer" => "autocomplete",
                    "search_analyzer" => "ascii_folding"
                ]
            ]
        ],
        "biography" => [
            "type" => "text",
            "analyzer" => "ascii_folding",
            "fields" => [
                "stemmed" => [
                    "type" => "text",
                    "analyzer" => "english"
                ]
            ]
        ],
        "nationality" => [
            "type" => "keyword",
            "fields" => [
                "folded" => [
                    "type" => "text",
                    "analyzer" => "ascii_folding"
                ]
            ]
        ],
        "place" => [
            "type" => "keyword",
            "fields" => [
                "folded" => [
                    "type" => "text",
                    "analyzer" => "ascii_folding"
                ]
            ]
        ],
        "role" => [
            "type" => "keyword",
            "fields" => [
                "folded" => [
                    "type" => "text",
                    "analyzer" => "ascii_folding"
                ]
            ]
        ],
        "birth_year" => [
            "type" => "integer",
        ],
        "death_year" => [
            "type" => "integer",
        ],
        "birth_place" => [
            "type" => "keyword",
        ],
        "death_place" => [
            "type" => "keyword",
        ],
        "sex" => [
            "type" => "keyword",
        ],
        "has_image" => [
            "type" => "boolean"
        ],
        "items_count" => [
            "type" => "integer"
        ],
        "items_with_images_count" => [
            "type" => "integer"
        ],
        "created_at" => [
            "type" => "date",
            "format" => "yyyy-MM-dd HH:mm:ss"
        ]
    ]
];

return [
    'en' => $mapping,
    'sk' => $mapping,
    'cs' => $mapping,
];