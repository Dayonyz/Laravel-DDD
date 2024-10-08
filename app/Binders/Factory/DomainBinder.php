<?php

namespace App\Binders\Factory;

use Illuminate\Contracts\Foundation\Application;

interface DomainBinder
{
    public function bind(Application $application);

    public function bindFoldersWithDefaultStructure(Application $application);

    public function getDomain(): string;

    public function getCurrentDomainPath(): string;
}