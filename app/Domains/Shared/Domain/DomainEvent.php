<?php

namespace App\Domains\Shared\Domain;

use App\Domains\Shared\Application\Enum\DomainEnum;
use App\Domains\Shared\Domain\Enum\DomainEventTypeEnum;

abstract class DomainEvent implements ConvertableEvent
{
    private array $data;
    private ?DomainEvent $discardEvent;
    public const REQUIRED_FIELDS = [
        'data',
        'event',
        'type',
        'occurredOn',
        'domain'
    ];

    final private function __construct(?DomainEvent $discardEvent = null)
    {
        $this->data = [];
        $this->data['data'] = [];
        $this->data['event'] = (new \ReflectionClass(get_called_class()))->getShortName();
        $this->data['type'] = DomainEventTypeEnum::SOURCE->value;
        $this->data['occurredOn'] = (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s');
        $this->data['domain'] = config('app.name');
        $this->discardEvent = $discardEvent;

        foreach ($this->getDomainHandlers() as $domain) {
            $this->assertIsValidDomain($domain);
        }

        if (!empty($this->getDomainHandlers()) && is_null($this->discardEvent)) {
            throw new \InvalidArgumentException('Discard event can not be empty with defined domain handlers');
        }
    }

    abstract public function getDomainHandlers(): array;

    public static function makeEvent(?DomainEvent $discardEvent = null, string|float|int|bool|null ...$args): static
    {
        $instance = new static($discardEvent);
        foreach ($args as $name => $value) {
            $instance->assertIsNamedArgument($name);
            $instance->setField($name, $value);
        }

        return $instance;
    }

    public function setField(string $field, string|float|int|bool|null $value): static
    {
        if (empty($field)) {
            throw new \InvalidArgumentException('Field name can not be empty');
        }

        $this->data['data'][$field] = $value;

        return $this;
    }

    public function setSection(string $section, string|float|int|bool|null ...$args): static
    {
        if (empty($section)) {
            throw new \InvalidArgumentException('Field name can not be empty');
        }

        $this->data[$section] = [];

        foreach ($args as $name => $value) {
            $this->assertIsNamedArgument($name);
            $this->data['data'][$section][$name] = $value;
        }

        return $this;
    }

    public function getEventName(): string
    {
        return $this->data['event'];
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function assertIsNamedArgument($name): void
    {
        if (!is_string($name))
            throw new \InvalidArgumentException('Only named parameters accepted');
    }

    public function assertIsValidDomain(string $domain): void
    {
        if (!in_array($domain, array_column(DomainEnum::cases(), 'value'))) {
            throw new \InvalidArgumentException('Invalid domain acceptor name');
        }
    }
}