<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Entities\Team\Team;
use App\Http\Resources\Team\TeamsCollection;

class TeamsController extends Controller
{
    public function getTeams(): TeamsCollection
    {
        return TeamsCollection::make(Team::all());
    }
}
