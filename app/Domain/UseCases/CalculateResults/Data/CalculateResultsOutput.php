<?php

declare(strict_types=1);

namespace App\Domain\UseCases\CalculateResults\Data;

use App\Domain\ValueObjects\PlayResults\PlayResults;

class CalculateResultsOutput
{
    public function __construct(
        public readonly PlayResults $playResults
    ) {
    }
}
