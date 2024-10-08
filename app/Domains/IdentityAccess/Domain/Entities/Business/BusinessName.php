<?php

namespace App\Domains\IdentityAccess\Domain\Entities\Business;

use App\Domains\Shared\Domain\Assertions;

class BusinessName
{
    use Assertions;
    private string $name;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    private function setName(string $name): void
    {
        $this->assertArgumentNotEmpty($name, 'Business display name can not be empty');
        $this->assertArgumentMinLength($name, 3, 'Minimal business display name length is 3 characters');
        $this->assertArgumentMaxLength($name, 64, 'Maximal business display name length is 64 characters');
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}