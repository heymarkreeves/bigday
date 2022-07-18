<?php

return [
    'trello_key' => env('TRELLO_KEY', false),
    'trello_token' => env('TRELLO_TOKEN', false),
    'board_slug' => env('TRELLO_BOARD', false),
    'card_fields' => [
        '62b366f41d55f03380fdcfe6' => [
            'db_field' => 'prior_customer',
            'type' => 'boolean'
        ],
        '62bd9a8aa7f1f34560ae3a10' => [
            'db_field' => 'email_address',
            'type' => 'string'
        ],
        '62bd9a985ddf7c069e76cccb' => [
            'db_field' => 'phone_number',
            'type' => 'phone' // strip chars
        ],
        '62b363e2b9dbd926f5f95793' => [
            'db_field' => 'status',
            'type' => 'lookup'
        ],
        '62b365df9fd3423183ae68fe' => [
            'db_field' => 'synergy_id',
            'type' => 'string'
        ],
        '62b3661c2147d236589dc586' => [
            'db_field' => 'venue',
            'type' => 'lookup'
        ],
        '62b366b3449c1e0cc50b0d36' => [
            'db_field' => 'lead_source',
            'type' => 'lookup'
        ],
        '62bd9a6f103a7f4537c8b53e' => [
            'db_field' => 'date_opened',
            'type' => 'datetime'
        ],
        '62b366ddb47d958002c34250' => [
            'db_field' => 'date_closed',
            'type' => 'datetime'
        ],
        '62b3670a8170d22d34c6753d' => [
            'db_field' => 'opportunity',
            'type' => 'dollars'
        ],
        '62b3673647dfd31e6233ff10' => [
            'db_field' => 'confidence',
            'type' => 'number'
        ],
        '62b3675432dbae6ba56e0ddd' => [
            'db_field' => 'final_billing',
            'type' => 'dollars'
        ],
        '62d1954daf84c961311c0e90' => [
            'db_field' => 'lead_type',
            'type' => 'lookup'
        ],
        '62d195a471d0d77b5f73f488' => [
            'db_field' => 'tour_date',
            'type' => 'datetime'
        ]
    ],
];