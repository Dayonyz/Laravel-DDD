<?php

namespace App\Domains\Shared\Application\Bus\Event;

use App\Domains\Shared\Application\Enum\EventHandledStatusEnum;
use App\Domains\Shared\Application\Events\DomainEventHandledEvent;
use App\Domains\Shared\Application\Exceptions\PayloadConvertException;
use App\Domains\Shared\Application\Facade\RequestHandler;
use App\Domains\Shared\Application\Facade\EventSourcePublisher;
use App\Domains\Shared\Domain\DomainEvent;
use Exception;
use Illuminate\Support\Facades\App;

abstract class BaseOuterEventSubscriber implements OuterEventSubscriber
{
    /**
     * @throws Exception
     */
    public function __call(string $handleMethod, array $arguments)
    {
        if ($handleMethod === 'handle' &&
            isset($arguments['id']) &&
            isset($arguments['payload']) &&
            $this->validatePayloadFields($arguments['payload'])
        ) {
            $payload = $arguments['payload'];
            $id = $arguments['id'];

            $method = $payload['domain'] . $payload ['event'];
            $payloadClass = app()->getNamespace() . 'Bus\\Event\\OuterEventPayloads\\' . $method;

            if (method_exists($this, $method) && class_exists($payloadClass)) {
                try {
                    $outerPayload = call_user_func($payloadClass . '::' . 'fromArray', $payload['data']);
                } catch (PayloadConvertException $exception) {
                    EventSourcePublisher::publish(DomainEventHandledEvent::makeEvent(
                        $id,
                        $payload['event'],
                        EventHandledStatusEnum::DECLINED,
                        $exception->getMessage()
                    ));

                    return;
                }

                try {
                    App::call([$this, $method], ['payload' => $outerPayload]);
                } catch (Exception $exception) {
                    EventSourcePublisher::publish(DomainEventHandledEvent::makeEvent(
                        $id,
                        $payload['event'],
                        EventHandledStatusEnum::FAILED,
                        $exception->getMessage()
                    ));
                    return;
                }

                try {
                    RequestHandler::complete();
                } catch (Exception $exception) {
                    throw new Exception($exception->getMessage());
                }

                EventSourcePublisher::publish(DomainEventHandledEvent::makeEvent(
                    $id,
                    $payload['event'],
                    EventHandledStatusEnum::SUCCESS,
                ));
            }
        }
    }

    private function validatePayloadFields(array $payload): bool
    {
        foreach (DomainEvent::REQUIRED_FIELDS as $field) {
            if (!isset($payload[$field]))
                return false;
        }

        return true;
    }
}