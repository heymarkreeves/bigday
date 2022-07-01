<?php

return [
    'trello_key' => env('TRELLO_KEY', false),
    'trello_token' => env('TRELLO_TOKEN', false),
    'board_slug' => env('TRELLO_BOARD', false),
    'card_fields' => [
        'trello_id' => 'db_field',
    ],
];