<?php

declare(strict_types=1);

namespace App\Domain\UseCases\GenerateFixtures\Data;

use Illuminate\Support\Collection;

class GenerateFixturesOutput
{
    public function __construct(
        public readonly Collection $fixtures
    ) {
    }
}
