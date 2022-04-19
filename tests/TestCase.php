<?php

namespace Tests;

use DateInterval;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Psr\SimpleCache\CacheInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private CacheInterface $cacheFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCache();
    }

    public function getCacheFaker(): CacheInterface
    {
        return $this->cacheFaker;
    }

    public function mockCache()
    {
        $this->cacheFaker = $this->app->instance(
            CacheInterface::class,
            new class implements CacheInterface {
                private array $storage = [];

                public function get(string $key, mixed $default = null): mixed
                {
                    return $this->storage[$key] ?? null;
                }

                public function set(string $key, mixed $value, DateInterval | int | null $ttl = null): bool
                {
                    $this->storage[$key] = $value;

                    return true;
                }

                public function delete(string $key): bool
                {
                    if (!isset($this->storage[$key])) {
                        return false;
                    }

                    unset($this->storage[$key]);

                    return true;
                }

                public function clear(): bool
                {
                    $this->storage = [];

                    return true;
                }

                public function getMultiple(iterable $keys, mixed $default = null): iterable
                {
                    $results = [];
                    foreach ($keys as $key) {
                        $results[$key] = $this->storage[$key] ?? null;
                    }

                    return $results;
                }

                public function setMultiple(iterable $values, DateInterval | int | null $ttl = null): bool
                {
                    foreach ($values as $key => $value) {
                        $this->storage[$key] = $value;
                    }

                    return true;
                }

                public function deleteMultiple(iterable $keys): bool
                {
                    foreach ($keys as $key) {
                        if (!isset($this->storage[$key])) {
                            continue;
                        }

                        unset($this->storage[$key]);
                    }

                    return true;
                }

                public function has(string $key): bool
                {
                    return isset($this->storage[$key]);
                }
            }
        );
    }
}
