<?php

return [
    'commands' => [
        \Illuminate\Foundation\Console\UpCommand::class,
        \Illuminate\Foundation\Console\DownCommand::class,
        \Illuminate\Cache\Console\ClearCommand::class,
    ],
    'auth' => [
        env('CLIENT_ARTISAN_REMOTE_API_KEY') => [
            \Illuminate\Foundation\Console\UpCommand::class,
            \Illuminate\Foundation\Console\DownCommand::class,
        ],
    ],
    'route_prefix' => 'artisan-remote',
];
