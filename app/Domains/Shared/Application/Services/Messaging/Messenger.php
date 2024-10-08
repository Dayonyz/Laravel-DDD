<?php

namespace App\Domains\Shared\Application\Services\Messaging;

interface Messenger
{
    public function send($channel, $data): mixed;
}
