<?php

declare(strict_types=1);

namespace App\Rate\Command;

use App\Coin\Enum\CoinEnum;
use App\Rate\Exception\DataFromProviderNotFound;
use App\Shared\Entity\Coin;
use App\Shared\Entity\Rate;
use App\Shared\Message\Query\DailyRatesRateQuery;
use App\Shared\MessageBus\QueryBusTrait;
use Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class RateHourlyUpdateCommand extends AbstractCommand
{
    use QueryBusTrait;

    protected static $defaultName = 'schedule:rate:hourly-update';
    protected static $defaultDescription = 'Update rate every hours';


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->handle($io);
            $this->cacheService->invalidateCash([Rate::CACHE_TAG]);
            $this->logger->info('Success hourly update');
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
            /** @var Coin $coinEntity */
            $coinEntity = $this->em->getRepository(Coin::class)->findOneBy(['name' => $coinName]);

            foreach ($coinEntity->getCurrencies() as $currency) {
                foreach ($this->getRateData($coinName, (string)$currency, $io) as $elem) {
                    $rateEntity = (new Rate())
                        ->setAmount($elem[1])
                        ->setCreateAt($elem[0]);

                    $this->em->persist($rateEntity);

                    $currency->addRate($rateEntity);
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
        $envelope = $this->query(new DailyRatesRateQuery($coinName, $currency, '0', 'hour'));

        $handledStamp = $envelope->last(HandledStamp::class);

        if (null === $handledStamp) {
            throw new DataFromProviderNotFound();
        }

        $io->success(
            sprintf(
                'Send request for get current hour rate with coin %s and currency %s, result count %s',
                $coinName,
                $currency,
                count($handledStamp->getResult())
            )
        );

        foreach ($handledStamp->getResult() as $item) {
            yield $item;
        }
    }


}
