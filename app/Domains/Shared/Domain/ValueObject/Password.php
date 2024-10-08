<?php

namespace App\Domains\Shared\Domain\ValueObject;

use App\Domains\Shared\Domain\Enum\PasswordLevelEnum;
use Illuminate\Support\Facades\Hash;

abstract class Password
{
    protected string $passwordHash;
    protected string $passwordLevel;
    protected array $passwordExceptionMessages = [
        'WEAK' => 'Invalid password: min length is 8 characters, must contain lower case and digits',
        'MEDIUM' => 'Invalid password: min length is 8 characters, must contain lower and upper cases and digits',
        'STRONG' => 'Invalid password: min length is 8 characters, must contain lower and upper cases, special characters and digits'
    ];

    public function __construct(string $password, ?PasswordLevelEnum $passwordLevel = null)
    {
        $this->passwordLevel = $passwordLevel ? $passwordLevel->name : PasswordLevelEnum::MEDIUM->name;
        $this->setPassword($password);
    }

    public function changePassword(string $oldPassword, string $newPassword):void
    {
        if (!$this->assertIsEqualToString($oldPassword)) {
            throw new \InvalidArgumentException('Incorrect old password argument');
        }

        $this->setPassword($newPassword);
    }

    private function setPassword(string $password): void
    {
        $level = constant(PasswordLevelEnum::class . '::' .$this->passwordLevel);
        $validate = @preg_match($level->value, $password);

        if ($validate === false) {
            throw new \InvalidArgumentException('Incorrect password regular expression');
        }

        if ($validate === 0) {
            $message = $this->passwordExceptionMessages[$this->passwordLevel] ?? 'Invalid password';
            throw new \InvalidArgumentException($message);
        }

        $this->passwordHash = Hash::make($password);
    }

    public function assertIsEqualToString(string $password): bool
    {
        return Hash::check($password, $this->passwordHash);
    }
}