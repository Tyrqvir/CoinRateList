<?php

declare(strict_types=1);

namespace App\Shared\Traits;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    protected static string $alertMethod = 'alert';
    protected static string $warningMethod = 'warning';
    protected static string $infoMethod = 'info';
    protected static string $errorMethod = 'error';
    protected ?LoggerInterface $logger = null;

    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    protected function writeLog(string $methodName, string $message, array $context = [], string $channel = null): void
    {
        if ($this->logger && method_exists($this->logger, $methodName)) {
            $this->logger->{$methodName}($message, $context);
        }
    }
}