<?php

namespace App\Domains\Shared\Domain\ValueObject;

use Illuminate\Support\Str;

use App\Domains\Shared\Domain\Assertions;

abstract class Phone
{
    use Assertions;

    protected string $code;
    protected string $number;
    protected ?string $verificationCode;
    protected \DateTimeImmutable $verifiedAt;

    public function __construct(string $code, string $number)
    {
        $this->setCode($code);
        $this->setNumber($number);
        $this->setVerificationCode();
    }

    protected function setCode(string $code): void
    {
        if (!empty($code)) {
            $this->assertIsOnlyDigits($code, 2, 'Phone code minimal length is 2 digits, only digits accepted');
            $this->assertArgumentMaxLength($code, 5, 'Phone code maximal length is 5 digits');
            $this->code = $code;
        }
    }

    public function getCode(): string
    {
        return $this->code;
    }

    protected function setNumber(string $phone): void
    {
        $this->assertIsOnlyDigits($phone, 3, 'Phone number minimal length is 3 digits, only digits accepted');
        $this->assertArgumentMaxLength($phone, 18, 'Phone number maximal length is 18 digits');
        $this->number = $phone;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function toString(): string
    {
        return $this->code . $this->number;
    }

    public static function createVerified(string $code, string $number): static
    {
        $valueObject = new static($code, $number);
        $valueObject->verify($valueObject->verificationCode());

        return $valueObject;
    }

    public function verify(string $code): void
    {
        if ($code !== $this->verificationCode) {
            throw new \InvalidArgumentException('Invalid verification code');
        }

        $this->verificationCode = null;
        $this->verifiedAt = new \DateTimeImmutable();
    }

    protected function setVerificationCode(): void
    {
        $this->verificationCode = strtolower(Str::random(5));
    }

    public function verificationCode(): string
    {
        return $this->verificationCode;
    }

    public function verifiedAt(): \DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function assertIsOnlyDigits($argument, int $minLength, string $message): void
    {
        $regExp = "/\d{" . $minLength . ",}/";
        $result = @preg_match($regExp, $argument);

        if ($result === false) {
            throw new \InvalidArgumentException('Incorrect phone regular expression');
        }

        if ($result === 0) {
            throw new \InvalidArgumentException($message);
        }
    }
}