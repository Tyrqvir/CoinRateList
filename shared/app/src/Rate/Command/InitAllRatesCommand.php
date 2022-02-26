<?php

declare(strict_types=1);

namespace App\Rate\Command;

use App\Coin\Enum\CoinEnum;
use App\Rate\Exception\DataFromProviderNotFound;
use App\Shared\Entity\Coin;
use App\Shared\Entity\Currency;
use App\Shared\Entity\Rate;
use App\Shared\Message\Query\DailyRatesRateQuery;
use App\Shared\Message\Query\RangeRatesRateQuery;
use App\Shared\MessageBus\QueryBusTrait;
use DateInterval;
use DateTimeImmutable;
use Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Stamp\HandledStamp;


class InitAllRatesCommand extends AbstractCommand
{
    use QueryBusTrait;

    protected static $defaultName = 'rate:init';
    protected static $defaultDescription = 'Init rates';
    protected static int $sendPackCount = 0;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->handle($io);
        } catch (\Exception $e) {
            $this->logger->error(__METHOD__, [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'message ' => $e->getMessage(),
            ]);
        }


        return Command::SUCCESS;
    }


    /**
     * @throws DataFromProviderNotFound
     */
    private function handle(OutputInterface $io)
    {
        foreach (CoinEnum::COINS as $coinName => $currencyForCoin) {
            $coinEntity = (new Coin())->setName($coinName);
            $this->em->persist($coinEntity);

            foreach ($currencyForCoin as $currency) {
                $currencyEntity = (new Currency())->setName($currency);
                $this->em->persist($currencyEntity);

                $coinEntity->addCurrency($currencyEntity);

                foreach ($this->getRateData($coinName, $currency, $io) as $rate) {
                    $rateEntity = (new Rate())
                        ->setAmount($rate[1])
                        ->setCreateAt($rate[0]);

                    $this->em->persist($rateEntity);

                    $currencyEntity->addRate($rateEntity);
                }
            }
        }

        $this->em->flush();
    }

    /**
     * @throws DataFromProviderNotFound
     */
    private function getRateData($coinName, string $currency, OutputInterface $io): Generator
    {
        foreach ($this->dailyDataGenerator($coinName, $currency, $io) as $dailyData) {
            [$dayTimestamp] = $dailyData;
            yield $dailyData;
            //Закоментил т.к из за rate limit на клауде, это будет очень долго, сделал не почасовой а дневной формат.
            //            foreach ($this->getHourlyDataGenerator($dayTimestamp, $coinName, $currency, $io) as $hourlyData) {
            //                yield $hourlyData;
            //            }
        }
    }

    private function dailyDataGenerator($coinName, string $currency, OutputInterface $io): Generator
    {
        $envelope = $this->query(new DailyRatesRateQuery($coinName, $currency));

        $io->success(sprintf('Send request for get daily rates with coin %s and currency %s', $coinName, $currency));

        $handledStamp = $envelope->last(HandledStamp::class);

        if (null === $handledStamp) {
            throw new DataFromProviderNotFound();
        }

        foreach ($handledStamp->getResult() as $item) {
            yield $item;
        }
    }

    private function getHourlyDataGenerator($dailyData, $coinName, string $currency, OutputInterface $io): Generator
    {
        $startDate = (new DateTimeImmutable())
            ->setTimestamp($dailyData / 1000);

        $endDate = $startDate->add(new DateInterval('P10D'));

        $envelope = $this->query(new RangeRatesRateQuery($coinName, $currency, $startDate->getTimestamp(), $endDate->getTimestamp()));

        $handledStamp = $envelope->last(HandledStamp::class);

        if (null === $handledStamp) {
            throw new DataFromProviderNotFound();
        }

        self::$sendPackCount++;

        $io->success(
            sprintf(
                'Send request for get hourly rates with coin %s, currency %s, start timestamp %d, end timestamp %d, pack %s, send iterate %s',
                $coinName,
                $currency,
                $startDate->getTimestamp(),
                $endDate->getTimestamp(),
                count($handledStamp->getResult()),
                self::$sendPackCount
            )
        );

        foreach ($handledStamp->getResult() as $item) {
            yield $item;
        }
    }


}
