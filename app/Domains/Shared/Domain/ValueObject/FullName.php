<?php

namespace App\Domains\Shared\Domain\ValueObject;

use App\Domains\Shared\Domain\Assertions;

abstract class FullName
{
    use Assertions;
    protected string $firstName;
    protected string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
    }

    public static function createFromString(string $fullName): static
    {
        static::assertIsFullName($fullName);

        $parsed = explode(' ', $fullName);

        $firstName = array_shift($parsed);
        $lasName = array_pop($parsed);

        return new static(trim($firstName), trim($lasName));
    }

    protected static function assertIsFullName(string $argument): void
    {
        $validate = @preg_match('/^\s*[a-z]+\s+[a-z]+\s*$/i', trim($argument));

        if ($validate === false) {
            throw new \InvalidArgumentException('Incorrect full name regular expression');
        }

        if ($validate === 0) {
            throw new \InvalidArgumentException("Full name must be in 'FirstName LastName' format");
        }
    }

    protected function setFirstName(string $firstName): void
    {
        $this->assertArgumentNotEmpty($firstName, 'First name can not be empty');
        $this->assertArgumentMinLength($firstName, 2, 'Minimal first name length is 2 characters');
        $this->assertArgumentMaxLength($firstName, 64, 'Maximal first name length is 64 characters');
        $this->firstName = $firstName;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    protected function setLastName(string $lastName): void
    {
        $this->assertArgumentNotEmpty($lastName, 'Last name can not be empty');
        $this->assertArgumentMinLength($lastName, 2, 'Minimal last name length is 2 characters');
        $this->assertArgumentMaxLength($lastName, 64, 'Maximal last name length is 64 characters');
        $this->lastName = $lastName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}