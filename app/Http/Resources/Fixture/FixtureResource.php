<?php

declare(strict_types=1);

namespace App\Http\Resources\Fixture;

use App\Domain\Entities\Fixture\Fixture;
use App\Http\Resources\Team\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Fixture */
class FixtureResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'week'              => $this->week,
            'first_team'        => TeamResource::make($this->firstTeam),
            'second_team'       => TeamResource::make($this->secondTeam),
            'first_team_goals'  => $this->first_team_goals,
            'second_team_goals' => $this->second_team_goals,
            'won_team_id'       => $this->won_team_id,
            'status'            => $this->status,
        ];
    }
}
