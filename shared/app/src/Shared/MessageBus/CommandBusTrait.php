<?php

declare(strict_types=1);

namespace App\Shared\MessageBus;

use App\Shared\Message\Contracts\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

trait CommandBusTrait
{
    /** @required */
    public MessageBusInterface $commandBus;

    public function query(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}