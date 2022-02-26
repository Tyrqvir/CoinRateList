<?php

declare(strict_types=1);

namespace App\Rate\Factory\RateProvider;

use App\Shared\Message\Contracts\RateQueryInterface;

interface RateProviderInterface
{
    public function handle(RateQueryInterface $message): array;
}