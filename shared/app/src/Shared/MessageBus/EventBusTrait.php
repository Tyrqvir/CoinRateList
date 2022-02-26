<?php

declare(strict_types=1);

namespace App\Shared\MessageBus;

use App\Shared\Message\Contracts\EventInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

trait EventBusTrait
{
    /** @required */
    public MessageBusInterface $eventBus;

    public function query(EventInterface $event): Envelope
    {
        return $this->eventBus->dispatch($event);
    }
}