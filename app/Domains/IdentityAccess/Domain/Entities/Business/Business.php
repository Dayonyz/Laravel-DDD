<?php

namespace App\Domains\IdentityAccess\Domain\Entities\Business;

use App\Domains\IdentityAccess\Domain\Entities\Business\ValueObjects\BusinessAddress;
use App\Domains\IdentityAccess\Domain\Entities\Business\ValueObjects\BusinessEmail;
use App\Domains\IdentityAccess\Domain\Entities\Business\ValueObjects\BusinessLogo;
use App\Domains\IdentityAccess\Domain\Entities\Business\ValueObjects\BusinessName;
use App\Domains\IdentityAccess\Domain\Entities\Business\ValueObjects\BusinessPhone;
use App\Domains\IdentityAccess\Domain\Entities\Business\ValueObjects\BusinessUuid;
use App\Domains\IdentityAccess\Domain\Entities\Business\ValueObjects\BusinessWebsite;
use App\Domains\IdentityAccess\Domain\Entities\User\Dto\UserCreateDto;
use App\Domains\Shared\Domain\Entity;
use App\Domains\IdentityAccess\Application\Bus\Command\Dto\CreateBusinessAccountDto;
use App\Domains\IdentityAccess\Domain\Entities\User\ValueObjects\UserUuid;
use App\Domains\IdentityAccess\Domain\Entities\User\Enums\UserTitleEnum;
use App\Domains\IdentityAccess\Domain\Factories\Account;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class Business extends Entity implements Account
{
    protected string $businessUuid;
    protected BusinessName $businessName;
    protected BusinessLogo $businessLogo;
    protected BusinessEmail $businessEmail;
    protected BusinessPhone $businessPhone;
    protected BusinessAddress $businessAddress;
    protected BusinessWebsite $businessWebsite;
    protected Collection $businessUsers;
    protected bool $businessIsActive;

    private function __construct(
        BusinessName    $businessName,
        BusinessLogo    $businessLogo,
        BusinessEmail   $businessEmail,
        BusinessPhone   $businessPhone,
        BusinessAddress $businessAddress,
        BusinessWebsite $businessWebsite,
        bool            $businessIsActive
    ) {
        parent::__construct();
        $this->setBusinessUuid(BusinessUuid::random());
        $this->setBusinessName($businessName);
        $this->setBusinessLogo($businessLogo);
        $this->setBusinessEmail($businessEmail);
        $this->setBusinessPhone($businessPhone);
        $this->setBusinessAddress($businessAddress);
        $this->setBusinessWebsite($businessWebsite);
        $this->businessUsers = new ArrayCollection();
        $this->businessIsActive = $businessIsActive;
    }

    private function __clone(): void
    {
        // TODO: Implement __clone() method.
    }

    public function getBusinessUsers(): Collection
    {
        return $this->businessUsers;
    }

    abstract protected function createBusinessUser(
        UserCreateDto $userDto
    ): BusinessUser;

    public static function createBusinessAccount(CreateBusinessAccountDto $dto): static {
        $business = new static(
            new BusinessName($dto->businessName),
            new BusinessLogo($dto->businessLogo),
            new BusinessEmail($dto->businessEmail),
            new BusinessPhone($dto->businessPhoneCode, $dto->businessPhone),
            new BusinessAddress(
                $dto->businessPostCode,
                $dto->businessCountryIso,
                $dto->businessCity,
                $dto->businessAddressLine1,
                $dto->businessAddressLine2
            ),
            new BusinessWebsite($dto->businessWebsite),
            $dto->businessIsActive,
        );

        $user = $business->createBusinessUser(
            new UserCreateDto(
                UserTitleEnum::tryFrom($dto->userTitle),
                $dto->userFirstName,
                $dto->userLastName,
                $dto->userEmail ? : $dto->businessEmail,
                $dto->userPhone && $dto->userPhoneCode ? $dto->userPhoneCode : $dto->businessPhoneCode,
                $dto->userPhone && $dto->userPhoneCode ?  $dto->userPhone : $dto->businessPhone,
                $dto->userPassword,
                $dto->userAvatar,
                $dto->userDisplayName,
                $dto->businessIsActive
            )
        );

        $business->addUser($user);

        return $business;
    }

    protected function setBusinessUuid(BusinessUuid $businessUuid) {
        $this->businessUuid = $businessUuid->toString();
    }

    public function getBusinessUuid(): string
    {
        return $this->businessUuid;
    }

    protected function setBusinessName(BusinessName $businessName): void
    {
        $this->businessName = $businessName;
    }

    public function getBusinessName(): BusinessName
    {
        return $this->businessName;
    }

    protected function setBusinessLogo(BusinessLogo $logo): void
    {
        $this->businessLogo = $logo;
    }

    public function getBusinessLogo(): ?BusinessLogo
    {
        return $this->businessLogo;
    }

    protected function setBusinessEmail(BusinessEmail $businessEmail): void
    {
        $this->businessEmail = $businessEmail;
    }

    public function getBusinessEmail(): BusinessEmail
    {
        return $this->businessEmail;
    }

    protected function setBusinessPhone(BusinessPhone $businessPhone): void
    {
        $this->businessPhone = $businessPhone;
    }

    public function getBusinessPhone(): BusinessPhone
    {
        return $this->businessPhone;
    }

    protected function setBusinessAddress(BusinessAddress $businessAddress): void
    {
        $this->businessAddress = $businessAddress;
    }

    public function getBusinessAddress(): BusinessAddress
    {
        return $this->businessAddress;
    }

    protected function setBusinessWebsite(BusinessWebsite $businessWebsite): void
    {
        $this->businessWebsite = $businessWebsite;
    }

    public function getBusinessWebsite(): BusinessWebsite
    {
        return $this->businessWebsite;
    }

    public function addUser(BusinessUser $businessUser): void
    {
        if (!$this->businessUsers->contains($businessUser)) {
            $this->businessUsers->add($businessUser);
        }
    }

    public function removeUser(UserUuid $uuid): void
    {
        $this->businessUsers->remove($uuid->toString());
    }

    public function getBusinessIsActive(): bool
    {
        return $this->businessIsActive;
    }

    public function activate(): void
    {
       $this->businessIsActive = true;
    }

    public function deactivate(): void
    {
        $this->businessIsActive = false;
    }
}