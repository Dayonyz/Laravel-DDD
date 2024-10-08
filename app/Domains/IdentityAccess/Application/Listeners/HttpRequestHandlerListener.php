<?php

namespace App\Domains\IdentityAccess\Application\Listeners;

use App\Domains\IdentityAccess\Application\Facade\RequestHandler;
use Exception;

class HttpRequestHandlerListener
{
    /**
     * Handle the event.
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            RequestHandler::complete();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
