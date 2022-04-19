<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\FixturePlayResult;

final class FixturePlayResult
{
    private float $firstTeamGoals  = 0;
    private float $secondTeamGoals = 0;
    private float $weight          = 0;

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
