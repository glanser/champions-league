<?php

declare(strict_types=1);

namespace App\Domain\UseCases\CalculateResults;

use App\Domain\Entities\Fixture\Fixture;
use App\Domain\Enum\CacheKey;
use App\Domain\UseCases\CalculateResults\Data\CalculateResultsOutput;
use App\Domain\ValueObjects\PlayResults\PlayResults;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CalculateResults implements CalculateResultsInterface
{
    public function __construct(
        private CacheInterface $cache
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function execute(bool $recalculate = false): CalculateResultsOutput
    {
        if (!$recalculate && $this->cache->has(CacheKey::Results->value)) {
            $cachedData = json_decode($this->cache->get(CacheKey::Results->value), true);
            $results    = PlayResults::deserialize($cachedData);
        } else {
            $fixtures = Fixture::all();
            $results  = PlayResults::make($fixtures);
            if (count($results) !== 0) {
                $this->cache->set(CacheKey::Results->value, json_encode($results->getIterator()));
            }
        }

        return new CalculateResultsOutput($results);
    }
}
