<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\PlayPredictions;

use App\Domain\ValueObjects\PlayResults\PlayResult;
use JsonSerializable;

final class PlayPrediction implements JsonSerializable
{
    public function __construct(
        private int $teamId,
        private string $teamName,
        private int $played,
        private int $won,
        private int $draw,
        private int $lost,
        private int $goalsFor,
        private int $goalsAgainst,
    ) {
    }

    public static function make(PlayResult $playResult): self
    {
        return new self(
            teamId:       $playResult->getTeamId(),
            teamName:     $playResult->getTeamName(),
            played:       $playResult->getPlayed(),
            won:          $playResult->getWon(),
            draw:         $playResult->getDraw(),
            lost:         $playResult->getLost(),
            goalsFor:     $playResult->getGoalsFor(),
            goalsAgainst: $playResult->getGoalsAgainst(),
        );
    }

    public function update($goalsFor, $goalsAgainst): self
    {
        $this->played++;

        if ($goalsFor == $goalsAgainst) {
            $this->draw++;
        } elseif ($goalsFor > $goalsAgainst) {
            $this->won++;
        } else {
            $this->lost++;
        }

        $this->goalsFor     += $goalsFor;
        $this->goalsAgainst += $goalsAgainst;

        return $this;
    }

    public function getTeamId(): int
    {
        return $this->teamId;
    }

    public function getTeamName(): string
    {
        return $this->teamName;
    }

    public function getPlayed(): int
    {
        return $this->played;
    }

    public function getWon(): int
    {
        return $this->won;
    }

    public function getDraw(): int
    {
        return $this->draw;
    }

    public function getLost(): int
    {
        return $this->lost;
    }

    public function getGoalsFor(): int
    {
        return $this->goalsFor;
    }

    public function getGoalsAgainst(): int
    {
        return $this->goalsAgainst;
    }

    public function getPoints(): int
    {
        return $this->won * 3 + $this->draw;
    }

    public function getGoalDifference(): int
    {
        return $this->goalsFor - $this->goalsAgainst;
    }

    public function jsonSerialize(): array
    {
        return [
            'team_id'       => $this->teamId,
            'team_name'     => $this->teamName,
            'played'        => $this->played,
            'won'           => $this->won,
            'draw'          => $this->draw,
            'lost'          => $this->lost,
            'goals_for'     => $this->goalsFor,
            'goals_against' => $this->goalsAgainst,
        ];
    }
}
