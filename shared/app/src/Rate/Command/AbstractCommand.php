<?php

declare(strict_types=1);

namespace App\Rate\Command;

use App\Shared\Service\CacheService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    protected EntityManagerInterface $em;
    protected LoggerInterface $logger;
    protected CacheService $cacheService;

    public function __construct(string $name = null, EntityManagerInterface $em, LoggerInterface $cronLogger, CacheService $cacheService)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->logger = $cronLogger;
        $this->cacheService = $cacheService;
    }

    protected function configure(): void
    {
        $this->setDescription(static::$defaultDescription);
    }
}