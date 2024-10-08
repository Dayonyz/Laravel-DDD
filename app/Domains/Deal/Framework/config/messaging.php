<?php

return [
    'redis' => [
        'connection' => 'stream',
        'drive' => 'phpredis',
    ],
    'support_msgpack' => true,
    'block_timeout' => 1000,
];
