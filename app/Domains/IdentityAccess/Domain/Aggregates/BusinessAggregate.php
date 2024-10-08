<?php

namespace App\Domains\IdentityAccess\Domain\Aggregates;

use App\Domains\IdentityAccess\Domain\Entities\Business\Business;
use App\Domains\IdentityAccess\Domain\Entities\Business\BusinessUser;
use App\Domains\IdentityAccess\Domain\Entities\User\UserAvatar;
use App\Domains\IdentityAccess\Domain\Entities\User\UserDisplayName;
use App\Domains\IdentityAccess\Domain\Entities\User\UserEmail;
use App\Domains\IdentityAccess\Domain\Entities\User\UserFullName;
use App\Domains\IdentityAccess\Domain\Entities\User\UserPassword;
use App\Domains\IdentityAccess\Domain\Entities\User\UserPhone;
use App\Domains\IdentityAccess\Domain\Enums\UserTitleEnum;
use App\Domains\IdentityAccess\Domain\Events\BusinessAccountRegistrationDiscarded;
use App\Domains\IdentityAccess\Domain\Events\BusinessAccountRegistered;
use App\Domains\IdentityAccess\Domain\Exceptions\UserEmailNotDefinedException;

class BusinessAggregate extends Business
{
    /**
     * @throws UserEmailNotDefinedException
     */
    protected function createBusinessUser(
        UserTitleEnum    $userTitle,
        UserFullName     $userFullName,
        UserEmail        $userEmail,
        UserPhone        $userPhone,
        UserPassword     $userPassword,
        ?UserAvatar      $userAvatar,
        ?UserDisplayName $displayName,
        ?bool             $userIsActive,
    ): BusinessUser {
        return BusinessUser::createBusinessUser(
            $this,
            $userTitle,
            $userFullName,
            $userEmail,
            $userPhone,
            $userPassword,
            $userAvatar,
            $displayName,
            $userIsActive
        );
    }

    public function onPrePersist(): void
    {
        $this->saveEvent(BusinessAccountRegistered::makeEvent(
            BusinessAccountRegistrationDiscarded::makeEvent(),
            name: $this->getBusinessName()->getName(),
            email: $this->getBusinessEmail()->getEmail()
        ));

        parent::onPrePersist();
    }
}