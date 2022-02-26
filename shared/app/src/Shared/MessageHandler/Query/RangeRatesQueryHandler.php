<?php

declare(strict_types=1);

namespace App\Shared\MessageHandler\Query;

use App\Rate\Factory\RateProvider\RateProviderInterface;
use App\Shared\Message\Query\RangeRatesRateQuery;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RangeRatesQueryHandler implements MessageHandlerInterface
{

    private RateProviderInterface $rates;

    public function __construct(RateProviderInterface $ratesByRange)
    {
        $this->rates = $ratesByRange;
    }

    public function __invoke(RangeRatesRateQuery $message): array
    {
        return $this->rates->handle($message);
    }

}