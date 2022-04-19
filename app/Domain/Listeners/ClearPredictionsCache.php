<?php

declare(strict_types=1);

namespace App\Domain\Listeners;

use App\Domain\Enum\CacheKey;
use App\Domain\Events\WeekPlayed;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class ClearPredictionsCache
{
    private WeekPlayed $event;

    public function __construct(
        private CacheInterface $cache
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(WeekPlayed $event): void
    {
        $this->event = $event;
        $this->cache->delete(CacheKey::Predictions->value);
    }

    public function getEvent(): WeekPlayed
    {
        return $this->event;
    }
}
