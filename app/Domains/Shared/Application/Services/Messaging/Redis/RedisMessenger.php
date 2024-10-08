<?php

namespace App\Domains\Shared\Application\Services\Messaging\Redis;

use App\Domains\Shared\Application\Services\Messaging\Messenger;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Redis\RedisManager;

class RedisMessenger implements  Messenger
{
    private Connection $redis;
    private string $dbPrefix;
    private mixed $supportMsgPack;

    public function __construct(?string $dbPrefix = null)
    {
        $this->dbPrefix = $dbPrefix ? : config('database.redis.options.prefix');
        $this->redis = $this->getRedis();
        $this->supportMsgPack = config('messaging.support_msgpack', false);
    }

    public function send($channel, $data): string
    {
        $data = $this->prepareMessage($data);

        return $this->redis->xADD($channel, '*', ['data' => $data]);

    }

    public function prepareMessage($data): bool|string
    {
        if ($this->supportMsgPack and function_exists('msgpack_pack')) {
            return msgpack_pack($data);
        }

        return json_encode($data);
    }

    public function getRedis(): Connection
    {
        $config = config('database.redis');
        $config['options']['prefix'] = $this->dbPrefix;
        $connectionName = config('messaging.redis.connection');
        $driveName = config('messaging.redis.drive', 'phpredis');

        $redisManager = new RedisManager(app(), $driveName, $config);

        return $redisManager->connection($connectionName);
    }
}
