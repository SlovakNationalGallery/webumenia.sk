<?php

return [
    'hosts' => [
        [
            'host' => env('ES_HOST', 'localhost'),
            'port' => env('ES_PORT', '9200'),
        ],
    ],
];
