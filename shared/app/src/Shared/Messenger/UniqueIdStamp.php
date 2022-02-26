<?php

declare(strict_types=1);

namespace App\Shared\Messenger;

use Symfony\Component\Messenger\Stamp\StampInterface;

class UniqueIdStamp implements StampInterface
{
    private string $uniqueId;

    public function __construct()
    {
        $this->uniqueId = uniqid('', true);
    }

    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }
}
