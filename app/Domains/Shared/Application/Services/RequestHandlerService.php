<?php

namespace App\Domains\Shared\Application\Services;

use App\Domains\Shared\Application\Bus\Command\CommandBus;
use App\Domains\Shared\Application\Bus\Event\EventBus;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

abstract class RequestHandlerService
{
    private CommandBus $commandBus;
    private EntityManagerInterface $entityManager;
    private EventBus $eventBus;

    public function __construct(
        CommandBus $commandBus,
        EntityManagerInterface $entityManager,
        EventBus $eventBus
    ){
        $this->commandBus = $commandBus;
        $this->entityManager = $entityManager;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws Exception
     */
    public function complete(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->commandBus->executeCommands();
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (Exception $exception) {
            $this->entityManager->getConnection()->rollBack();
            throw $exception;
        }

        try {
            $this->eventBus->publishEvents();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        try {
            $this->entityManager->flush();
        } catch (Exception $exception) {
            $this->eventBus->discardEvents();
            throw new Exception($exception->getMessage());
        }

        try {
            $this->eventBus->handleDeferredSubscribers();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}