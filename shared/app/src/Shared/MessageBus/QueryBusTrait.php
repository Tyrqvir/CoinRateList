<?php

declare(strict_types=1);

namespace App\Shared\MessageBus;

use App\Shared\Message\Contracts\QueryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

trait QueryBusTrait
{
    /** @required */
    public MessageBusInterface $queryBus;

    public function query(QueryInterface $query)
    {
        return $this->queryBus->dispatch($query);
    }
}