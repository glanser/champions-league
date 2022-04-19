<?php

declare(strict_types=1);

namespace App\Domain\UseCases\MakePrediction\Data;

use App\Domain\ValueObjects\PlayPredictions\PlayPredictions;

class MakePredictionOutput
{
    public function __construct(
        public readonly PlayPredictions $playPredictions
    ) {
    }
}
