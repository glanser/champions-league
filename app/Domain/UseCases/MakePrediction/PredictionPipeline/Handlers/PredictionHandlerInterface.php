<?php

declare(strict_types=1);

namespace App\Domain\UseCases\MakePrediction\PredictionPipeline\Handlers;

use App\Domain\UseCases\MakePrediction\PredictionPipeline\Dto\PipelineDto;
use App\Domain\ValueObjects\FixturePlayPrediction\FixturePlayPrediction;
use Closure;

interface PredictionHandlerInterface
{
    public function handle(PipelineDto $pipelineDto, FixturePlayPrediction $fixturePlayPrediction, Closure $next): void;
}
