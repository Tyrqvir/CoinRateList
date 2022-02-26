<?php

declare(strict_types=1);

namespace App\Shared\Service;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final class CacheService
{
    private AsciiSlugger $slugger;
    private TagAwareCacheInterface $cache;

    public function __construct(SluggerInterface $slugger, TagAwareCacheInterface $cache)
    {
        $this->slugger = $slugger;
        $this->cache = $cache;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getCache(string $keyName, callable $callback, bool $isClearCache = false)
    {
        $key = $this->generateCacheKey($keyName);
        $key = $this->sanitizeKey($key);

        $beta = $isClearCache ? INF : null;

        return $this->cache->get(
            $key,
            $callback,
            $beta
        );
    }

    private function generateCacheKey(string $keyName): string
    {
        return sprintf(
            '%s',
            $keyName,
        );
    }

    private function sanitizeKey(string $key): string
    {
        return $this->slugger->slug($key)->lower()->toString();
    }

    public function invalidateCash(array $tag): void
    {
        $this->cache->invalidateTags($tag);
    }

}