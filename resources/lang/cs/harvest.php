<?php

return [
    'status_messages' => [
        'waiting' => 'Čeká na zpracování',
        'started' => 'Zahájeno',
        'progress' => 'Zpracováva se (:current/:total)',
        'error' => ':error',
        'completed' =>
            'Zpracovaných záznamů: :processed' . PHP_EOL .
            ':created nových' . PHP_EOL .
            ':updated upravených' . PHP_EOL .
            ':deleted smazaných' . PHP_EOL .
            ':skipped přeskočených' . PHP_EOL .
            ':failed selhalo.' . PHP_EOL .
            'Trvalo to :time s',
    ],
];
