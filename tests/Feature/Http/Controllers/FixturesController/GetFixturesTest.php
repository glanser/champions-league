<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\FixturesController;

use App\Domain\Entities\Fixture\Fixture;
use App\Domain\Entities\Team\Team;
use Database\Factories\FixtureFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GetFixturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array[]
     */
    private array $predictions;
    private array $expectedResult;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Collection<Team> teams */
        $teams = TeamFactory::new()->count(4)->create();

        $fixtures = collect();
        foreach ($teams as $team) {
            $fixtures = $fixtures->merge(
                FixtureFactory::new()->count(4)->create(
                    [
                        'first_team_id'  => $team->id,
                        'second_team_id' => $teams
                            ->where('id', '!=', $team->id)
                            ->random()
                            ->id,
                    ]
                )
            );
        }

        $this->expectedResult = $fixtures->map(
            static fn(Fixture $fixture) => [
                'id'                => $fixture->id,
                'week'              => $fixture->week,
                'first_team'        => [
                    'id'              => $fixture->firstTeam->id,
                    'name'            => $fixture->firstTeam->name,
                    'played'          => $fixture->firstTeam->played,
                    'won'             => $fixture->firstTeam->won,
                    'draw'            => $fixture->firstTeam->draw,
                    'lost'            => $fixture->firstTeam->lost,
                    'goal_difference' => $fixture->firstTeam->goal_difference,
                ],
                'second_team'       => [
                    'id'              => $fixture->secondTeam->id,
                    'name'            => $fixture->secondTeam->name,
                    'played'          => $fixture->secondTeam->played,
                    'won'             => $fixture->secondTeam->won,
                    'draw'            => $fixture->secondTeam->draw,
                    'lost'            => $fixture->secondTeam->lost,
                    'goal_difference' => $fixture->secondTeam->goal_difference,
                ],
                'first_team_goals'  => $fixture->first_team_goals,
                'second_team_goals' => $fixture->second_team_goals,
                'won_team_id'       => $fixture->won_team_id,
                'status'            => $fixture->status->value,
            ]
        )->toArray();
    }

    public function testGetFixtures(): void
    {
        $response = $this->get(route('fixtures.get'));
        $response->assertOk();

        $response->assertJson(['data' => $this->expectedResult]);
    }
}
