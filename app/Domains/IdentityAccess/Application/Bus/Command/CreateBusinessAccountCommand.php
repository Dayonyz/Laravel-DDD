<?php

namespace App\Domains\IdentityAccess\Application\Bus\Command;

use App\Domains\IdentityAccess\Application\Bus\Command\Dto\CreateBusinessAccountDto;
use App\Domains\IdentityAccess\Domain\Aggregates\BusinessAggregate;
use Doctrine\ORM\EntityManagerInterface;

class CreateBusinessAccountCommand extends Command
{
    public function handle(CreateBusinessAccountDto $payload, EntityManagerInterface $entityManager)
    {
        $business = BusinessAggregate::createBusinessAccount($payload);

        $entityManager->persist($business);
    }
}