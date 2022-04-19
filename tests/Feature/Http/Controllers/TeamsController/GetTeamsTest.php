<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\TeamsController;

use App\Domain\Entities\Team\Team;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GetTeamsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection<Team>|Team|Team[] $teams
     */
    private Collection | array | Team $teams;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Collection<Team> teams */
        $this->teams = TeamFactory::new()->count(4)->create();
    }

    public function testGetTeams(): void
    {
        $expectedData = [];

        foreach ($this->teams as $team) {
            $expectedData[] = [
                'id'              => $team->id,
                'name'            => $team->name,
                'played'          => $team->played,
                'won'             => $team->won,
                'draw'            => $team->draw,
                'lost'            => $team->lost,
                'goal_difference' => $team->goal_difference,
            ];
        }

        $response = $this->get(route('teams.get'));
        $response->assertOk();

        $response->assertJson(['data' => $expectedData]);
    }
}
