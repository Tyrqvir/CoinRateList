<?php

declare(strict_types=1);

namespace App\Tests\functional\MessageHandler\Query;

use App\Rate\Exception\DataFromProviderNotFound;
use App\Rate\Factory\RateProvider\IntervalProvider;
use App\Shared\Message\Query\DailyRatesRateQuery;
use App\Shared\MessageHandler\Query\DailyRatesQueryHandler;
use App\Tests\FunctionalTester;
use Codeception\Example;
use JsonException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DailyRatesMessageHandlerCest
{

    /**
     * @dataProvider successProviderData
     *
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface|JsonException
     * @throws DataFromProviderNotFound
     */
    public function testDailyRatesMessageHandler(FunctionalTester $I, Example $coinInfo): void
    {
        $createMessage = new DailyRatesRateQuery($coinInfo['coin'], $coinInfo['pair']);

        $data = [
            'prices' => [
                [
                    1367107200000,
                    135.3,
                ],
                [
                    1367193600000,
                    141.96,
                ],
            ],
        ];
        $mockResponse = new MockResponse(json_encode($data, JSON_THROW_ON_ERROR), [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $client = new MockHttpClient([
            $mockResponse,
        ]);

        $container = $I->grabService('kernel')->getContainer();
        $container->set(HttpClientInterface::class, $client);

        $messageHandler = new DailyRatesQueryHandler($I->grabService(IntervalProvider::class));
        /** @var MockResponse $responseData */
        $responseData = $messageHandler($createMessage);

        $I->assertSame(Response::HTTP_OK, $mockResponse->getStatusCode());
        $I->assertSame('GET', $mockResponse->getRequestMethod());
        $I->assertSame(sprintf('https://api.coingecko.com/api/v3/coins/%s/market_chart?vs_currency=%s&days=max&interval=daily', $coinInfo['coin'], $coinInfo['pair']), $mockResponse->getRequestUrl());
        $I->assertContains('application/json', $mockResponse->getHeaders()['content-type']);
        $I->assertIsArray($responseData);
        $I->assertNotEmpty($responseData);
    }

    /**
     * @dataProvider failedProviderData
     *
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws JsonException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testFailedDailyRatesMessageHandler(FunctionalTester $I, Example $coinInfo): void
    {
        $createMessage = new DailyRatesRateQuery($coinInfo['coin'], $coinInfo['pair']);

        $validator = $I->grabService(ValidatorInterface::class);
        $errors = $validator->validate($createMessage);

        $I->assertEquals($errors->count(), $coinInfo['errorCount']);
    }


    protected function successProviderData(): array
    {
        return [
            ['coin' => 'bitcoin', 'pair' => 'usd'],
            ['coin' => 'bitcoin', 'pair' => 'eur'],
            ['coin' => 'bitcoin', 'pair' => 'gbp'],
        ];
    }

    protected function failedProviderData(): array
    {
        return [
            ['coin' => '', 'pair' => 'usd', 'errorCount' => 1],
            ['coin' => 'bitcoin', 'pair' => '', 'errorCount' => 1],
            ['coin' => '', 'pair' => '', 'errorCount' => 2],
        ];
    }
}