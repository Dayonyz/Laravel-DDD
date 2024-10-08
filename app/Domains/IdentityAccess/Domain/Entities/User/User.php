<?php

namespace App\Domains\IdentityAccess\Domain\Entities\User;

use App\Domains\Shared\Domain\Entity;
use App\Domains\Shared\Domain\ValueObject\Password;
use App\Domains\IdentityAccess\Domain\Enums\UserAccountTypeEnum;
use App\Domains\IdentityAccess\Domain\Enums\UserTitleEnum;
use App\Domains\IdentityAccess\Domain\Exceptions\UserEmailNotDefinedException;

abstract class User extends Entity
{
    protected UserUuid $userUuid;
    protected string $userTitle;
    protected ?UserDisplayName $userDisplayName;
    protected UserFullName $userFullName;
    protected ?UserAvatar $userAvatar;
    protected string $userAccountType;
    protected UserEmail $userEmail;
    protected UserPhone $userPhone;
    protected UserPassword $userPassword;

    /**
     * @throws UserEmailNotDefinedException
     */
    public function __construct(
        UserTitleEnum       $title,
        UserFullName        $fullName,
        UserEmail           $email,
        UserPhone           $phone,
        UserPassword        $password,
        ?UserAvatar         $avatar,
        ?UserDisplayName    $displayName,
    ) {
        parent::__construct();
        $this->setUserUuid(UserUuid::random());
        $this->setUserAccountType(static::getUserAccountTypeEnum());
        $this->setUserTitle($title);
        $this->setUserDisplayName($displayName);
        $this->setUserFullName($fullName);
        $this->setUserAvatar($avatar);
        $this->setUserEmail($email);

        if (!$displayName) {
            $this->setDisplayNameFromEmail();
        }

        $this->setUserPhone($phone);
        $this->setUserPassword($password);
    }

    private function __clone(): void
    {
        // TODO: Implement __clone() method.
    }

    abstract public static function getUserAccountTypeEnum(): UserAccountTypeEnum;

    protected function setUserUuid(UserUuid $userUuid): void
    {
        $this->userUuid = $userUuid;
    }

    public function getUserUuid(): UserUuid
    {
        return $this->userUuid;
    }

    protected function setUserTitle(UserTitleEnum $titleEnum): void
    {
        $this->userTitle = $titleEnum->value;
    }

    public function getUserTitle(): string
    {
        return $this->userTitle;
    }

    /**
     * @throws UserEmailNotDefinedException
     */
    protected function setDisplayNameFromEmail(): void
    {
        if (!$this->userEmail) {
            throw new UserEmailNotDefinedException('User display name can not be parsed from empty email');
        }

        $parsed  = explode('@', $this->userEmail->getEmail());
        $this->userDisplayName = new UserDisplayName($parsed[0]);
    }

    protected function setUserDisplayName(?UserDisplayName $userDisplayName): void
    {
        $this->userDisplayName = $userDisplayName;
    }

    public function getUserDisplayName(): ?UserDisplayName
    {
        return $this->userDisplayName;
    }

    protected function setUserFullName(UserFullName $userFullName): void
    {
        $this->userFullName = $userFullName;
    }

    public function getUserFullName(): UserFullName
    {
        return $this->userFullName;
    }

    protected function setUserAvatar(?UserAvatar $userAvatar): void
    {
        $this->userAvatar = $userAvatar;
    }

    public function getUserAvatar(): ?UserAvatar
    {
        return $this->userAvatar;
    }

    protected function setUserAccountType(UserAccountTypeEnum $userAccountType): void
    {
        $this->userAccountType = $userAccountType->value;
    }

    public function getUserAccountType(): string
    {
        return $this->userAccountType;
    }

    protected function setUserEmail(UserEmail $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    public function getUserEmail(): UserEmail
    {
        return $this->userEmail;
    }

    protected function setUserPhone(UserPhone $userPhone): void
    {
        $this->userPhone = $userPhone;
    }

    public function getUserPhone(): UserPhone
    {
        return $this->userPhone;
    }

    protected function setUserPassword(Password $userPassword): void
    {
        $this->userPassword = $userPassword;
    }

    public function getUserPassword(): UserPassword
    {
        return $this->userPassword;
    }
}