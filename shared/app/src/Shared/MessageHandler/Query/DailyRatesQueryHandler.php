<?php

declare(strict_types=1);

namespace App\Shared\MessageHandler\Query;

use App\Rate\Factory\RateProvider\RateProviderInterface;
use App\Shared\Message\Query\DailyRatesRateQuery;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DailyRatesQueryHandler implements MessageHandlerInterface
{

    private RateProviderInterface $rates;

    public function __construct(RateProviderInterface $ratesByInterval)
    {
        $this->rates = $ratesByInterval;
    }

    public function __invoke(DailyRatesRateQuery $message): array
    {
        return $this->rates->handle($message);
    }
}