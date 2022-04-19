<?php

declare(strict_types=1);

namespace App\Domain\UseCases\MakePrediction\PredictionPipeline;

use App\Domain\UseCases\MakePrediction\PredictionPipeline\Dto\PipelineDto;
use App\Domain\UseCases\MakePrediction\PredictionPipeline\Handlers\PredictionHandlerInterface;
use App\Domain\ValueObjects\FixturePlayPrediction\FixturePlayPrediction;

interface PredictionPipelineInterface
{
    public function setHandlers(PredictionHandlerInterface ...$handlers): self;

    public function start(PipelineDto $pipelineDto): FixturePlayPrediction;
}
