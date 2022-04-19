<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\FixturePlayPrediction;

use App\Domain\Entities\Fixture\Fixture;

final class FixturePlayPrediction
{
    private float $firstTeamGoals  = 0;
    private float $secondTeamGoals = 0;
    private float $weight          = 0;

    public function __construct(
        public readonly Fixture $fixture
    ) {
    }

    public function modify(float $firstTeamGoals, float $secondTeamGoals, float $weight): void
    {
        $this->firstTeamGoals  += $firstTeamGoals * $weight;
        $this->secondTeamGoals += $secondTeamGoals * $weight;
        $this->weight          += $weight;
    }

    public function getFirstTeamGoals(): int
    {
        return (int) floor($this->firstTeamGoals);
    }

    public function getSecondTeamGoals(): int
    {
        return (int) floor($this->secondTeamGoals);
    }
}
