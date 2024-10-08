<?php

namespace App\Domains\IdentityAccess\Application\Bus\Command;
use App\Domains\IdentityAccess\Domain\Aggregates\BusinessAggregate;
use Doctrine\ORM\EntityManagerInterface;

class CreateBusinessAccountBaseCommand extends Command
{
    public function handle(CreateBusinessAccountPayload $payload, EntityManagerInterface $entityManager)
    {
        $business = BusinessAggregate::createBusinessAccount($payload);

        $entityManager->persist($business);
    }
}