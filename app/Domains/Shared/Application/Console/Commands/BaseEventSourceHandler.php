<?php

namespace App\Domains\Shared\Application\Console\Commands;

use App\Domains\Shared\Application\Bus\Event\OuterEventSubscriber;
use App\Domains\Shared\Application\Facade\EventSourceMessageReceiver;
use App\Domains\Shared\Domain\Enum\DomainEventTypeEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;

abstract class BaseEventSourceHandler extends Command
{
    protected $signature = 'event-source:handle';
    protected $description = 'run event-source handler';

    private string $domainName;
    private string $eventChannel;
    private OuterEventSubscriber $subscriber;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    #[NoReturn]
    public function __construct(OuterEventSubscriber $subscriber)
    {
        parent::__construct();
        $this->domainName = Str::snake(config('app.name'));
        $this->eventChannel  = config('event-source.channel');
        $this->subscriber = $subscriber;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        while (true)  {
            $messages = EventSourceMessageReceiver::getMessages(
                $this->eventChannel,
                EventSourceMessageReceiver::getMessageCursor($this->eventChannel, $this->domainName)
            );
            foreach ($messages as $id => $message) {
                if ($message['type'] === DomainEventTypeEnum::SOURCE->value) {
                    try {
                        $this->subscriber->handle(id: $id, payload: $message);
                    } catch (\Exception $exception) {
                        EventSourceMessageReceiver::setMessageCursor($this->eventChannel, $this->domainName ,$id);
                        $this->info("Message handled with exception - message ID: '{$id}', exception: '{$exception->getMessage()}'");
                        continue;
                    }
                }

                EventSourceMessageReceiver::setMessageCursor($this->eventChannel, $this->domainName ,$id);
                $this->info("Message handled - message ID: [{$id}]");
            }
        }
    }
}
