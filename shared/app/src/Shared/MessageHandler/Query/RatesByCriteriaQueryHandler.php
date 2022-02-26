<?php

declare(strict_types=1);

namespace App\Shared\MessageHandler\Query;

use App\Rate\Repository\RateRepository;
use App\Shared\Entity\Rate;
use App\Shared\Message\Query\RatesByCriteriaQuery;
use App\Shared\Service\CacheService;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Cache\ItemInterface;

final class RatesByCriteriaQueryHandler implements MessageHandlerInterface
{

    private CacheService $cacheService;
    private RateRepository $rateRepository;
    private SerializerInterface $serializerService;

    public function __construct(CacheService $cacheService, RateRepository $rateRepository, SerializerInterface $serializerService)
    {
        $this->cacheService = $cacheService;
        $this->rateRepository = $rateRepository;
        $this->serializerService = $serializerService;
    }

    public function __invoke(RatesByCriteriaQuery $message): array
    {
        return $this->cacheService->getCache(
            md5($this->serializerService->serialize($message, 'json')),
            function (ItemInterface $item) use ($message) {
                $item->tag([Rate::CACHE_TAG]);
                $item->expiresAfter(1800);

                return $this->rateRepository->findByCriteria($message);
            }
        );
    }

}