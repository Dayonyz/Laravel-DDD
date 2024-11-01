<?php

namespace App\Domains\Shared\Application\Bus\Command;

use App\Domains\Shared\Application\Bus\ConvertablePayload;

abstract class BaseCommandDto implements CommandDtoContract
{
    use ConvertablePayload;
}