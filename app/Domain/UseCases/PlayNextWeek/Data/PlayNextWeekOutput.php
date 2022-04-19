<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek\Data;

use Illuminate\Support\Collection;

class PlayNextWeekOutput
{
    public function __construct(
        public readonly Collection $fixtures,
    ) {
    }
}
