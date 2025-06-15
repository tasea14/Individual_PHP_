<?php
return [
    'db' => [
        'dsn' => 'sqlite:' . __DIR__ . '/../data/database.sqlite',
        'user' => null,
        'password' => null,
    ],
    'auth' => [
        'session_name' => 'mamin_recepty_session',
        'cookie_lifetime' => 3600 * 24 * 7, // 7 дней
    ],
];
