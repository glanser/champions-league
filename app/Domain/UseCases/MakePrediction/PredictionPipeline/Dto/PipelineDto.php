<?php

declare(strict_types=1);

namespace App\Domain\UseCases\MakePrediction\PredictionPipeline\Dto;

use App\Domain\Entities\Fixture\Fixture;
use App\Domain\ValueObjects\PlayPredictions\PlayPredictions;

final class PipelineDto
{
    public function __construct(
        public readonly Fixture $currentFixture,
        public readonly PlayPredictions $playPredictions,
    ) {
    }
}
