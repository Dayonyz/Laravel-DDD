<?php

namespace App\Domains\Shared\Application\Events;

use App\Domains\Shared\Application\Enum\EventHandledStatusEnum;
use App\Domains\Shared\Domain\ConvertableEvent;
use App\Domains\Shared\Domain\Enum\DomainEventTypeEnum;

class DomainEventHandledEvent implements ConvertableEvent
{
    protected string $handledEventId;
    protected string $handledEvent;
    protected string $status;
    protected ?string $message;
    protected \DateTimeImmutable $occurredOn;
    protected array $data;

    final private function __construct(string $handledEventId, string $handledEvent, EventHandledStatusEnum $status)
    {
        $this->setHandledEventId($handledEventId)
            ->setHandledEvent($handledEvent)
            ->setStatus($status);

        $this->occurredOn = new \DateTimeImmutable('now');
        $this->data = [];
    }

    public static function makeEvent(
        string                 $handledEventId,
        string                 $handledEvent,
        EventHandledStatusEnum $status,
        string                 $message = '',
        string|float|int|bool|null ...$args
    ): static {
        $instance = new static($handledEventId, $handledEvent, $status);

        if (empty($message) && in_array($status, [
            EventHandledStatusEnum::DECLINED->value,
            EventHandledStatusEnum::FAILED->value]
        )) {
            throw new \InvalidArgumentException('Message can not be empty');
        }

        if (!empty($message)) {
            $instance->setMessage($message);
        }

        foreach ($args as $name => $value) {
            $instance->assertIsNamedArgument($name);
            $instance->setField($name, $value);
        }

        return $instance;
    }

    protected function setField(string $field, string|float|int|bool|null $value): static
    {
        if (empty($field)) {
            throw new \InvalidArgumentException('Field name can not be empty');
        }

        $this->data[$field] = $value;

        return $this;
    }

    protected function setHandledEventId(string $id): static
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('Handle event name can not be empty');
        }
        $this->handledEventId = $id;

        return $this;
    }

    protected function setHandledEvent(string $eventName): static
    {
        if (empty($eventName)) {
            throw new \InvalidArgumentException('Handle event name can not be empty');
        }
        $this->handledEvent = $eventName;

        return $this;
    }

    protected function setStatus(EventHandledStatusEnum $status): static
    {
        $this->status = $status->value;

        return $this;
    }

    protected function setMessage(string $message): static
    {
        if (empty($message)) {
            throw new \InvalidArgumentException('Message can not be empty');
        }

        $this->message = $message;

        return $this;
    }

    public function assertIsNamedArgument($name): void
    {
        if (!is_string($name))
            throw new \InvalidArgumentException('Only named parameters accepted');
    }

    public function toArray(): array
    {
        $data = get_object_vars($this);
        $data['event'] = (new \ReflectionClass(get_called_class()))->getShortName();
        $data['type'] = DomainEventTypeEnum::HANDLER->value;
        $data['occurredOn'] = $this->occurredOn->format('Y-m-d H:i:s');
        $data['domain'] = config('app.name');

        return $data;
    }
}