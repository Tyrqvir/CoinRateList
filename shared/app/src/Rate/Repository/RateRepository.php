<?php

declare(strict_types=1);

namespace App\Rate\Repository;

use App\Shared\Entity\Coin;
use App\Shared\Entity\Currency;
use App\Shared\Entity\Rate;
use App\Shared\Message\Query\RatesByCriteriaQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rate[]    findAll()
 * @method Rate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    public function findByCriteria(RatesByCriteriaQuery $message)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('rate.amount, rate.createAt')
            ->from(Coin::class, 'coin')
            ->leftJoin(
                Currency::class,
                'currency',
                Join::WITH,
                'coin.id = currency.id'
            )
            ->leftJoin(
                Rate::class,
                'rate',
                Join::WITH,
                'currency.id = rate.currency'
            )
            ->andWhere('coin.name = :coinName')
            ->andWhere('currency.name = :currencyName')
            ->andWhere('rate.createAt BETWEEN :start AND :end')
            ->setParameter('coinName', $message->getCoin())
            ->setParameter('currencyName', $message->getCurrency())
            ->setParameter('start', $message->getStart())
            ->setParameter('end', $message->getEnd())
            ->orderBy('rate.createAt')
        ;

        return $qb->getQuery()->getResult();
    }
}
