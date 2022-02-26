<?php

declare(strict_types=1);

namespace App\Rate\Factory\RateProvider;

use App\Shared\Message\Contracts\RateQueryInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RangeProvider extends AbstractRateProvider
{
    public function getDataByRequest(RateQueryInterface $message): ResponseInterface
    {
        return $this->client->request(
            'GET',
            sprintf(
                '%s/coins/%s/market_chart/range?vs_currency=%s&from=%d&to=%d',
                static::$endpointPrefix,
                $message->getCoin(),
                $message->getCurrency(),
                $message->getStart(),
                $message->getEnd(),
            )
        );
    }
}