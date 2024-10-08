<?php

namespace App\Domains\Shared\Application\Services\Messaging\Redis;

use App\Domains\Shared\Application\Services\Messaging\MessageReceiver;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Redis\RedisManager;

class RedisMessageReceiver implements MessageReceiver
{
    private Connection $redis;
    private string $dbPrefix;
    private mixed $supportMsgPack;
    private ?int $blockTimeout;

    public function __construct(?string $dbPrefix = null)
    {
        $this->dbPrefix = $dbPrefix ? : config('database.redis.options.prefix') ;
        $this->redis = $this->getRedis();
        $this->supportMsgPack = config('messaging.support_msgpack', false);
        $this->blockTimeout = config('messaging.block_timeout', null);
    }
    public function getMessages($channel, $sinceId = '0'): array
    {
        $messages = $this->redis->xRead([$channel => $sinceId], null, $this->blockTimeout);
        $output = [];

        if (!empty($messages) && isset($messages[$this->dbPrefix . $channel])) {
            $channelMessages = $messages[$this->dbPrefix . $channel];
            foreach ($channelMessages as $id => $message) {
                if (isset($message['data'])) {
                    if ($this->supportMsgPack and function_exists('msgpack_unpack')) {
                        $output[$id] = msgpack_unpack($message['data']);
                    } else {
                        $output[$id] = json_decode($message['data'], 1) ?: $message['data'];
                    }
                }
            }
        }

        return $output;
    }

    public function setMessageCursor($channel, $listener, $id): void
    {
        $this->redis->set($channel . '_' . $listener, $id);
    }

    public function getMessageCursor($channel, $listener): string
    {
        $value = $this->redis->get($channel . '_' . $listener);

        return $value ?? '0';
    }

    public function getRedis(): Connection
    {
        $config = config('database.redis');
        $config['options']['prefix'] = $this->dbPrefix;
        $connectionName = config('messaging.redis.connection');
        $driveName = config('messaging.redis.drive', 'phpredis');

        $redisManager = new  RedisManager(app(), $driveName, $config);

        return $redisManager->connection($connectionName);
    }
}