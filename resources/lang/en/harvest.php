<?php

return [
    'status_messages' => [
        'waiting' => 'Waiting in a queue',
        'started' => 'Started',
        'progress' => 'In progress (:current/:total)',
        'error' => ':error',
        'completed' =>
            'Processed records: :processed' . PHP_EOL .
            ':created created' . PHP_EOL .
            ':updated updated' . PHP_EOL .
            ':deleted deleted' . PHP_EOL .
            ':skipped skipped' . PHP_EOL .
            ':failed failed.' . PHP_EOL .
            'Took :time s',
    ],
];