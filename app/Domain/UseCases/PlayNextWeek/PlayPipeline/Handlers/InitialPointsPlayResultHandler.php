<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek\PlayPipeline\Handlers;

use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Dto\PipelineDto;
use App\Domain\ValueObjects\FixturePlayResult\FixturePlayResult;
use Closure;

class InitialPointsPlayResultHandler implements PlayResultHandlerInterface
{
    public function __construct(
        private float $weight,
        private int $min,
        private int $max,
    ) {
    }

    public function handle(
        PipelineDto $pipelineDto,
        FixturePlayResult $fixturePlayResult,
        Closure $next
    ): void {
        $maxGoals = rand($this->min, $this->max);

        $firstTeamPoints  = $pipelineDto->currentFixture->firstTeam->getInitialPoints();
        $secondTeamPoints = $pipelineDto->currentFixture->secondTeam->getInitialPoints();

        $firstTeamGoals  = ($firstTeamPoints / ($firstTeamPoints + $secondTeamPoints)) * $maxGoals;
        $secondTeamGoals = ($secondTeamPoints / ($firstTeamPoints + $secondTeamPoints)) * $maxGoals;

        $fixturePlayResult->modify(
            $firstTeamGoals,
            $secondTeamGoals,
            $this->weight,
        );

        $next($pipelineDto, $fixturePlayResult);
    }
}
