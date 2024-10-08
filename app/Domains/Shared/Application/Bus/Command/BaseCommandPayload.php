<?php

namespace App\Domains\Shared\Application\Bus\Command;

use App\Domains\Shared\Application\Bus\ConvertablePayload;

abstract class BaseCommandPayload implements CommandPayloadContract
{
    use ConvertablePayload;
}