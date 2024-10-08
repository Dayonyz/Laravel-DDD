<?php

namespace App\Domains\Deal\Application\Listeners;

use App\Domains\Deal\Application\Facade\RequestHandler;
use Exception;

class RequestHandledListener
{
    /**
     * Handle the event.
     * @throws Exception
     */
    public function handle(): void
    {
        RequestHandler::complete();
//        try {
//            RequestHandler::complete();
//        } catch (Exception $exception) {
//            throw new Exception($exception->getMessage());
//        }
    }
}
