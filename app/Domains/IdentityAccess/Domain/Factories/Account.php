<?php

namespace App\Domains\IdentityAccess\Domain\Factories;

interface Account
{
    public function activate();

    public function deactivate();
}