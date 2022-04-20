<?php

declare(strict_types=1);

namespace App\Http\Resources\Team;

use App\Domain\Entities\Team\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Team */
class TeamResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'played'          => $this->played,
            'won'             => $this->won,
            'draw'            => $this->draw,
            'lost'            => $this->lost,
            'goal_difference' => $this->goal_difference,
            'points'          => $this->getInitialPoints(),
        ];
    }
}
