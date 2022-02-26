<?php

declare(strict_types=1);

namespace App\Rate\Factory\RateProvider;

use App\Shared\Message\Contracts\RateQueryInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class IntervalProvider extends AbstractRateProvider
{
    public function getDataByRequest(RateQueryInterface $message): ResponseInterface
    {
        return $this->client->request(
            'GET',
            sprintf(
                '%s/coins/%s/market_chart?vs_currency=%s&days=%s&interval=%s',
                static::$endpointPrefix,
                $message->getCoin(),
                $message->getCurrency(),
                $message->getDays(),
                $message->getInterval(),
            )
        );
    }
}