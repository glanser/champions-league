<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\FixturesController;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\Entities\Fixture\Fixture;
use App\Domain\Events\WeekPlayed;
use App\Domain\UseCases\PlayNextWeek\PlayNextWeek;
use App\Domain\UseCases\PlayNextWeek\PlayNextWeekInterface;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Dto\PipelineDto;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Handlers\PlayResultHandlerInterface;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\PlayPipeline;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\PlayPipelineInterface;
use App\Domain\ValueObjects\FixturePlayResult\FixturePlayResult;
use Closure;
use Database\Factories\FixtureFactory;
use Database\Factories\TeamFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PlayNextWeekTest extends TestCase
{
    use RefreshDatabase;

    private Fixture $nonAffectedWeek;
    private Fixture $affectedWeek;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $handler = new class implements PlayResultHandlerInterface {
            public function handle(PipelineDto $pipelineDto, FixturePlayResult $fixturePlayResult, Closure $next): void
            {
                $fixturePlayResult->modify(1, 0, 1);
            }
        };

        /** @var PlayPipelineInterface $pipeline */
        $pipeline = $this->app->make(PlayPipeline::class);
        $pipeline->setHandlers($handler);

        $this->app->instance(
            PlayNextWeekInterface::class,
            $this->app->makeWith(
                PlayNextWeek::class,
                ['pipeline' => $pipeline]
            )
        );

        $firstTeam  = TeamFactory::new()->create();
        $secondTeam = TeamFactory::new()->create();

        $this->affectedWeek = FixtureFactory::new()->create(
            [
                'status'            => FixtureStatus::Planned,
                'first_team_id'     => $firstTeam->id,
                'second_team_id'    => $secondTeam->id,
                'first_team_goals'  => null,
                'second_team_goals' => null,
                'week'              => 1,
            ],
        );

        $this->nonAffectedWeek = FixtureFactory::new()->create(
            [
                'status'            => FixtureStatus::Planned,
                'first_team_id'     => $firstTeam->id,
                'second_team_id'    => $secondTeam->id,
                'first_team_goals'  => null,
                'second_team_goals' => null,
                'week'              => 2,
            ],
        );
    }

    public function testPlayNextWeek(): void
    {
        $response = $this->post(route('fixtures.play-next'));
        $response->assertOk();

        Event::assertDispatched(WeekPlayed::class, 1);

        $response->assertJson(
            [
                'data' => [
                    [
                        'id'                => $this->affectedWeek->id,
                        'week'              => $this->affectedWeek->week,
                        'first_team'        => [
                            'id'              => $this->affectedWeek->firstTeam->id,
                            'name'            => $this->affectedWeek->firstTeam->name,
                            'played'          => $this->affectedWeek->firstTeam->played,
                            'won'             => $this->affectedWeek->firstTeam->won,
                            'draw'            => $this->affectedWeek->firstTeam->draw,
                            'lost'            => $this->affectedWeek->firstTeam->lost,
                            'goal_difference' => $this->affectedWeek->firstTeam->goal_difference,
                        ],
                        'second_team'       => [
                            'id'              => $this->affectedWeek->secondTeam->id,
                            'name'            => $this->affectedWeek->secondTeam->name,
                            'played'          => $this->affectedWeek->secondTeam->played,
                            'won'             => $this->affectedWeek->secondTeam->won,
                            'draw'            => $this->affectedWeek->secondTeam->draw,
                            'lost'            => $this->affectedWeek->secondTeam->lost,
                            'goal_difference' => $this->affectedWeek->secondTeam->goal_difference,
                        ],
                        'first_team_goals'  => 1,
                        'second_team_goals' => 0,
                        'won_team_id'       => $this->affectedWeek->firstTeam->id,
                        'status'            => FixtureStatus::Played->value,
                    ],
                ],
            ],
        );

        $this->assertDatabaseHas(
            'fixtures',
            [
                'id'                => $this->affectedWeek->id,
                'first_team_goals'  => 1,
                'second_team_goals' => 0,
                'won_team_id'       => $this->affectedWeek->firstTeam->id,
                'status'            => FixtureStatus::Played->value,
            ]
        );

        $this->assertDatabaseHas(
            'fixtures',
            [
                'id'                => $this->nonAffectedWeek->id,
                'first_team_goals'  => null,
                'second_team_goals' => null,
                'won_team_id'       => null,
                'status'            => FixtureStatus::Planned->value,
            ]
        );
    }

    public function testPlayNextWeekIfEveryWeekWasPlayed(): void
    {
        $this->affectedWeek->status = FixtureStatus::Played;
        $this->affectedWeek->save();

        $this->nonAffectedWeek->status = FixtureStatus::Played;
        $this->nonAffectedWeek->save();

        $response = $this->post(route('fixtures.play-next'));
        $response->assertStatus(409);
    }
}
