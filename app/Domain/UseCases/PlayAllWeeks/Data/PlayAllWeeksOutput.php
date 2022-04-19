<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayAllWeeks\Data;

use App\Domain\ValueObjects\PlayResults\PlayResults;

class PlayAllWeeksOutput
{
    public function __construct(
        public readonly PlayResults $playResults
    ) {
    }
}
