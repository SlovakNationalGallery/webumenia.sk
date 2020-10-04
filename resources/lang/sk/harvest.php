<?php

return [
    'status_messages' => [
        'waiting' => 'Čaká na spracovanie',
        'started' => 'Začaté',
        'progress' => 'Spracováva sa (:current/:total)',
        'error' => ':error',
        'finished' => 'Spracovaných bolo :processed záznamov',
        'completed' =>
            'Spracovaných bolo :processed záznamov.' . PHP_EOL .
            ':created nových záznamov' . PHP_EOL .
            ':updated bolo upravených' . PHP_EOL .
            ':deleted bolo zmazaných' . PHP_EOL .
            ':skipped bolo preskočených.' . PHP_EOL .
            'Trvalo to :time s',
    ],
];