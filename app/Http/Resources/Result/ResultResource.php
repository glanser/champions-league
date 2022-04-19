<?php

declare(strict_types=1);

namespace App\Http\Resources\Result;

use App\Domain\ValueObjects\PlayResults\PlayResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PlayResult */
class ResultResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'team_id'          => $this->getTeamId(),
            'team_name'        => $this->getTeamName(),
            'played'           => $this->getPlayed(),
            'won'              => $this->getWon(),
            'draw'             => $this->getDraw(),
            'lost'             => $this->getLost(),
            'goals_for'        => $this->getGoalsFor(),
            'goals_against'    => $this->getGoalsAgainst(),
            'goals_difference' => $this->getGoalDifference(),
            'points'           => $this->getPoints(),
        ];
    }
}
