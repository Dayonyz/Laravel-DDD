<?php

namespace App\Binders;

use App\Binders\Factory\AbstractDomainBinder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Domains\IdentityAccess\Interface\Http\Kernel as HttpKernel;
use App\Domains\IdentityAccess\Application\Console\Kernel as ConsoleKernel;
use App\Domains\IdentityAccess\Application\Exceptions\Handler;

class IdentityAccessDomainBinder extends AbstractDomainBinder
{
    public function bind(Application $application)
    {
       $this->bindFoldersWithDefaultStructure($application);

        $application->singleton(
            HttpKernelContract::class,
            HttpKernel::class
        );

        $application->singleton(
            ConsoleKernelContract::class,
            ConsoleKernel::class
        );

        $application->singleton(
            ExceptionHandler::class,
            Handler::class
        );
    }
}