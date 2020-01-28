<?php

return [
    'hosts' => [
        sprintf('http://%s:%d', env('ES_HOST'), env('ES_PORT'))
    ],
];