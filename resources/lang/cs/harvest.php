<?php

return [
    'status_messages' => [
        'progress' => 'Zpracováva se (:current/:total)',
        'error' => ':id selhalo ":message"',
        'completed' =>
            'Zpracovaných záznamů: :processed' . PHP_EOL .
            ':created nových' . PHP_EOL .
            ':updated upravených' . PHP_EOL .
            ':deleted smazaných' . PHP_EOL .
            ':skipped přeskočených.' . PHP_EOL .
            'Trvalo to :time s',
    ],
];