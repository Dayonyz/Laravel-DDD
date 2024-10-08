<?php

namespace App\Binders;

use App\Binders\Factory\AbstractDomainBinder;
use App\Domains\Deal\Application\Console\Kernel as ConsoleKernel;
use App\Domains\Deal\Application\Exceptions\Handler;
use App\Domains\Deal\Interface\Http\Kernel as HttpKernel;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;

class DealDomainBinder extends AbstractDomainBinder
{
    public function bind(Application $application)
    {
       $this->bindFoldersWithDefaultStructure($application);
        //useLangPath
        //usePublicPath

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