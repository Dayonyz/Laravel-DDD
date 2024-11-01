<?php

namespace App\Domains\IdentityAccess\Domain\Entities\User;

use App\Domains\Shared\Domain\Entity;
use App\Domains\Shared\Domain\ValueObject\Password;
use App\Domains\IdentityAccess\Domain\Enums\UserAccountTypeEnum;
use App\Domains\IdentityAccess\Domain\Enums\UserTitleEnum;
use App\Domains\IdentityAccess\Domain\Entities\User\Dto\UserDto;

abstract class User extends Entity
{
    protected UserUuid $uuid;
    protected string $title;
    protected UserFullName $fullName;
    protected string $accountType;
    protected UserEmail $email;
    protected UserPhone $phone;
    protected UserPassword $password;
    protected ?UserAvatar $avatar;
    protected ?UserDisplayName $displayName;
    protected bool $isActive;

    protected function __construct(
        UserDto $userDto,
    ) {
        parent::__construct();
        $this->setUuid(UserUuid::random());
        $this->setAccountType(static::getUserAccountTypeEnum());
        $this->setTitle($userDto->title);
        $this->setFullName(new UserFullName($userDto->firstName, $userDto->lastName));
        $this->setEmail(new UserEmail($userDto->email));
        $this->setPhone(new UserPhone($userDto->phoneCode, $userDto->phoneNumber));
        $this->setPassword(new UserPassword($userDto->password));

        if ($userDto->avatarUrl) {
            $this->setAvatar(new UserAvatar($userDto->avatarUrl));
        }

        if ($userDto->displayName) {
            $this->setDisplayName(new UserDisplayName($userDto->displayName));
        } else {
            $this->setDisplayNameFromEmail();
        }

        $this->setIsActive($userDto->isActive);
    }

    private function __clone(): void
    {
        // TODO: Implement __clone() method.
    }

    abstract public static function getUserAccountTypeEnum(): UserAccountTypeEnum;

    protected function setUuid(UserUuid $userUuid): void
    {
        $this->uuid = $userUuid;
    }

    public function getUuid(): UserUuid
    {
        return $this->uuid;
    }

    protected function setTitle(UserTitleEnum $titleEnum): void
    {
        $this->title = $titleEnum->value;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    protected function setDisplayNameFromEmail(): void
    {
        $parsed  = explode('@', $this->email->getEmail());
        $this->displayName = new UserDisplayName($parsed[0]);
    }

    protected function setDisplayName(?UserDisplayName $userDisplayName): void
    {
        $this->displayName = $userDisplayName;
    }

    public function getDisplayName(): ?UserDisplayName
    {
        return $this->displayName;
    }

    protected function setFullName(UserFullName $userFullName): void
    {
        $this->fullName = $userFullName;
    }

    public function getFullName(): UserFullName
    {
        return $this->fullName;
    }

    protected function setAvatar(?UserAvatar $userAvatar): void
    {
        $this->avatar = $userAvatar;
    }

    public function getAvatar(): ?UserAvatar
    {
        return $this->avatar;
    }

    protected function setAccountType(UserAccountTypeEnum $userAccountType): void
    {
        $this->accountType = $userAccountType->value;
    }

    public function getAccountType(): string
    {
        return $this->accountType;
    }

    protected function setEmail(UserEmail $userEmail): void
    {
        $this->email = $userEmail;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    protected function setPhone(UserPhone $userPhone): void
    {
        $this->phone = $userPhone;
    }

    public function getPhone(): UserPhone
    {
        return $this->phone;
    }

    protected function setPassword(Password $userPassword): void
    {
        $this->password = $userPassword;
    }

    protected function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }
}