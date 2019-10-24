<?php

return [
    'status_messages' => [
        'progress' => 'In progress (:current/:total)',
        'error' => ':id failed: ":message"',
        'completed' =>
            'Processed records: :processed' . PHP_EOL .
            ':created created' . PHP_EOL .
            ':updated updated' . PHP_EOL .
            ':deleted deleted' . PHP_EOL .
            ':skipped skipped.' . PHP_EOL .
            'Took :time s',
    ],
];