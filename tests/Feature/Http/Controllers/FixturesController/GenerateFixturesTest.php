<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\FixturesController;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\Entities\Team\Team;
use Config;
use Database\Factories\FixtureFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GenerateFixturesTest extends TestCase
{
    use RefreshDatabase;

    private array $expectedResult;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set(
            'fixtures',
            [
            'weeks'               => 2,
            'min_number_of_teams' => 2,
            ]
        );

        /** @var Collection<Team> teams */
        $teams    = TeamFactory::new()->count(2)->create();
        $teamsIds = $teams->pluck('id');

        $pairs = [
            [$teamsIds[0], $teamsIds[1]],
            [$teamsIds[1], $teamsIds[0]],
        ];

        foreach ($pairs as $key => $pair) {
            /** @var Team $firstTeam */
            $firstTeam = $teams->where('id', '=', $pair[0])->first();
            /** @var Team $secondTeam */
            $secondTeam = $teams->where('id', '=', $pair[1])->first();

            $this->expectedResult[] = [
                'id'                => $key + 1,
                'week'              => $key + 1,
                'first_team'        => [
                    'id'              => $firstTeam->id,
                    'name'            => $firstTeam->name,
                    'played'          => $firstTeam->played,
                    'won'             => $firstTeam->won,
                    'draw'            => $firstTeam->draw,
                    'lost'            => $firstTeam->lost,
                    'goal_difference' => $firstTeam->goal_difference,
                ],
                'second_team'       => [
                    'id'              => $secondTeam->id,
                    'name'            => $secondTeam->name,
                    'played'          => $secondTeam->played,
                    'won'             => $secondTeam->won,
                    'draw'            => $secondTeam->draw,
                    'lost'            => $secondTeam->lost,
                    'goal_difference' => $secondTeam->goal_difference,
                ],
                'first_team_goals'  => null,
                'second_team_goals' => null,
                'won_team_id'       => null,
                'status'            => FixtureStatus::Planned->value,
            ];
        }
    }

    public function testGenerateFixtures(): void
    {
        $response = $this->post(route('fixtures.generate'));
        $response->assertOk();

        $response->assertJson(['data' => $this->expectedResult]);
    }

    public function testGenerateFixturesIfAlreadyExists(): void
    {
        FixtureFactory::new()->create();

        $response = $this->post(route('fixtures.generate'));
        $response->assertStatus(409);
    }
}
