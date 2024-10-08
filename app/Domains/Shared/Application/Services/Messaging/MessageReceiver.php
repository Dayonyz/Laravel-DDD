<?php

namespace App\Domains\Shared\Application\Services\Messaging;

interface MessageReceiver
{
    public function getMessages($channel, $sinceId);
}