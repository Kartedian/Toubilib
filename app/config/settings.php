<?php

return [
    'settings' => [
        'db' => [
            'host' => getenv('DB_HOST') ?: 'toubilib.db',
            'name' => getenv('DB_NAME') ?: 'toubilib',
            'user' => getenv('DB_USER') ?: 'root',
            'pass' => getenv('DB_PASS') ?: 'root'
        ]
    ]
];