<?php

declare(strict_types=1);

namespace App\Domain\UseCases\ResetData;

use App\Domain\Entities\Fixture\Fixture;
use App\Domain\Enum\CacheKey;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class ResetFixtures implements ResetFixturesInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function execute(): void
    {
        foreach (CacheKey::cases() as $cacheKey) {
            $this->cache->delete($cacheKey->value);
        }

        Fixture::truncate();
    }
}
