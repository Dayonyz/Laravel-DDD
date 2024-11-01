<?php

namespace App\Domains\IdentityAccess\Domain\Aggregates;

use App\Domains\IdentityAccess\Domain\Entities\Business\Business;
use App\Domains\IdentityAccess\Domain\Entities\Business\BusinessUser;
use App\Domains\IdentityAccess\Domain\Entities\User\Dto\UserCreateDto;
use App\Domains\IdentityAccess\Domain\Events\BusinessAccountRegistrationDiscarded;
use App\Domains\IdentityAccess\Domain\Events\BusinessAccountRegistered;

class BusinessAggregate extends Business
{
    protected function createBusinessUser(
        UserCreateDto $userDto
    ): BusinessUser {
        return BusinessUser::createBusinessUser(
            $this,
            $userDto
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