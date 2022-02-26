<?php

declare(strict_types=1);

namespace App\Rate\Factory\RateProvider;

use App\Rate\Exception\DataFromProviderNotFound;
use App\Shared\Message\Contracts\RateQueryInterface;
use App\Shared\Traits\LoggerTrait;
use Exception;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractRateProvider implements RateProviderInterface
{
    use LoggerTrait;

    protected static string $endpointPrefix = 'https://api.coingecko.com/api/v3';
    protected HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle(RateQueryInterface $message): array
    {
        try {
            $response = $this->getDataByRequest($message);

            if (Response::HTTP_OK !== $response->getStatusCode()) {
                throw new TransportException(sprintf('Problem with get data about %s with currency %s', $message->getCoin(), $message->getCurrency()));
            }

            if (!array_key_exists('prices', $response->toArray())) {
                throw new DataFromProviderNotFound();
            }

            return $response->toArray()['prices'];
        } catch (Exception $e) {
            $this->writeLog(self::$warningMethod, __METHOD__, [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'message ' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get instrument for handle
     */
    abstract public function getDataByRequest(RateQueryInterface $message): ResponseInterface;
}