<?php

return [
    'mappings-paths' => [
        base_path() . '/app/Domains/Shared/Persistence/Doctrine/Metadata',
        dirname(__DIR__, 2) . '/Persistence/Doctrine/Metadata',
    ],
    'connection' => env('DOCTRINE_DB_CONNECTION', 'mysql'),
    'driver' => env('DOCTRINE_DB_DRIVER', 'mysql')
];