<?php

declare(strict_types=1);

namespace App\Domain\UseCases\MakePrediction\PredictionPipeline\Handlers;

use App\Domain\UseCases\MakePrediction\PredictionPipeline\Dto\PipelineDto;
use App\Domain\ValueObjects\FixturePlayPrediction\FixturePlayPrediction;
use Closure;

class InitialPointsPredictionHandler implements PredictionHandlerInterface
{
    public function __construct(
        private float $weight,
        private int $min,
        private int $max,
    ) {
    }

    public function handle(
        PipelineDto $pipelineDto,
        FixturePlayPrediction $fixturePlayPrediction,
        Closure $next
    ): void {
        $maxGoals = rand($this->min, $this->max);

        $firstTeamPoints  = $pipelineDto->currentFixture->firstTeam->getInitialPoints();
        $secondTeamPoints = $pipelineDto->currentFixture->secondTeam->getInitialPoints();

        $firstTeamGoals  = ($firstTeamPoints / ($firstTeamPoints + $secondTeamPoints)) * $maxGoals;
        $secondTeamGoals = ($secondTeamPoints / ($firstTeamPoints + $secondTeamPoints)) * $maxGoals;

        $fixturePlayPrediction->modify(
            $firstTeamGoals,
            $secondTeamGoals,
            $this->weight,
        );

        $next($pipelineDto, $fixturePlayPrediction);
    }
}
