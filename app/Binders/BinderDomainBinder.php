<?php

namespace App\Binders;

use App\Binders\Factory\AbstractDomainBinder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Http\Kernel as HttpKernel;
use App\Console\Kernel as ConsoleKernel;
use App\Exceptions\Handler;

class BinderDomainBinder extends AbstractDomainBinder
{
    public function bind(Application $application)
    {
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