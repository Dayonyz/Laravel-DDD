<?php

namespace App\Domains\Shared\Domain\ValueObject;

use Illuminate\Support\Str;
use App\Domains\Shared\Domain\Assertions;

abstract class Email
{
    use Assertions;

    protected string $email;
    protected ?string $verificationCode;
    protected \DateTimeImmutable $verifiedAt;

    public function __construct(string $email)
    {
        $this->setEmail($email);
        $this->setVerificationCode();
    }

    public static function createVerified(string $email): static
    {
        $emailValueObject = new static($email);
        $emailValueObject->verify($emailValueObject->verificationCode());

        return $emailValueObject;
    }

    public function verify(string $code): void
    {
        if ($code !== $this->verificationCode) {
            throw new \InvalidArgumentException('Invalid verification code');
        }

        $this->verificationCode = null;
        $this->verifiedAt = new \DateTimeImmutable();
    }


    protected function setEmail(string $email): void
    {
        $this->assertArgumentMaxLength($email, 320, 'Email maximal length is 320 characters');
        $this->assertIsEmail($email);
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    protected function assertIsEmail(string $argument): void
    {
        if (!filter_var($argument, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
    }

    public function verifiedAt(): \DateTimeImmutable
    {
        return $this->verifiedAt;
    }


    protected function setVerificationCode(): void
    {
        $this->verificationCode = Str::random(32);
    }

    public function verificationCode(): string
    {
        return $this->verificationCode;
    }

}