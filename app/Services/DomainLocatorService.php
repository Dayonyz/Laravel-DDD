<?php

namespace App\Services;

use App\Domains\Shared\Application\Enum\DomainEnum;
use Illuminate\Support\Str;

class DomainLocatorService
{
    public const DEFAULT_DOMAIN = DomainEnum::BINDER;

    public static function getName(bool $pullConsole = true): DomainEnum
    {
        if (self::isHttp()) {
            $path = explode('/', $_SERVER['REQUEST_URI']);

            if (isset($path[1]) && $path[1] !== '') {
                $domainName = ucfirst(Str::camel($path[1]));
                $domainEnum = DomainEnum::tryFrom($domainName);

                return $domainEnum ? : self::DEFAULT_DOMAIN;
            }
        } else {
            $lastArgAsDomain = ucfirst(Str::camel($_SERVER['argv'][count($_SERVER['argv']) - 1]));
            $domainEnum = DomainEnum::tryFrom($lastArgAsDomain);

            if ($domainEnum) {
                if ($pullConsole) {
                    array_pop($_SERVER['argv']);
                }

                return $domainEnum;
            }
        }

        return self::DEFAULT_DOMAIN;
    }

    private static function isHttp(): bool
    {
        return isset($_SERVER['REQUEST_URI']);
    }
}