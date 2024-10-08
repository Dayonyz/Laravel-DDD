<?php

namespace App\Domains\IdentityAccess\Domain\Entities\User;

use App\Domains\Shared\Domain\Assertions;

class UserDisplayName
{
    use Assertions;
    private string $displayName;

    public function __construct(string $displayName)
    {
        $this->setDisplayName($displayName);
    }

    private function setDisplayName(string $displayName): void
    {
        $this->assertArgumentNotEmpty($displayName, 'User display name can not be empty');
        $this->assertArgumentMinLength($displayName, 3, 'Minimal user display name length is 3 characters');
        $this->assertArgumentMaxLength($displayName, 64, 'Maximal user display name length is 3 characters');
        $this->displayName = $displayName;
    }

    public function toString(): string
    {
        return $this->displayName;
    }
}