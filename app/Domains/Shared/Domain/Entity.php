<?php

namespace App\Domains\Shared\Domain;

use App\Domains\Shared\Application\Bus\Event\EventBus;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class Entity
{
    protected \DateTimeImmutable $createdAt;
    protected \DateTimeImmutable $updatedAt;
    private array $domainEvents = [];

    protected function __construct(
        protected ?int $id = null,
    ) {
    }

    protected function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @throws BindingResolutionException
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->sendEventsToBus();
    }

    /**
     * @throws BindingResolutionException
     */
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable('now');
        $this->sendEventsToBus();
    }

    /**
     * @throws BindingResolutionException
     */
    private function sendEventsToBus(): void {
        /**
         * @var EventBus $eventBus
         */
        $eventBus= app()->make(EventBus::class);

        $eventBus->registerEvents(...$this->pullDomainEvents());
    }

    final protected function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function saveEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}