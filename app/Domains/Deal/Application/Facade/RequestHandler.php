<?php

namespace App\Domains\Deal\Application\Facade;

use Illuminate\Support\Facades\Facade;

class RequestHandler extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'request_handler';
    }
}