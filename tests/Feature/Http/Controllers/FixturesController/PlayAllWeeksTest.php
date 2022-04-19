<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\FixturesController;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\Entities\Team\Team;
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

class PlayAllWeeksTest extends TestCase
{
    use RefreshDatabase;

    private Team $firstTeam;
    private Team $secondTeam;

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

        $this->firstTeam  = TeamFactory::new()->create();
        $this->secondTeam = TeamFactory::new()->create();

        FixtureFactory::new()->create(
            [
                'status'            => FixtureStatus::Planned,
                'first_team_id'     => $this->firstTeam->id,
                'second_team_id'    => $this->secondTeam->id,
                'first_team_goals'  => null,
                'second_team_goals' => null,
                'week'              => 1,
            ],
        );

        FixtureFactory::new()->create(
            [
                'status'            => FixtureStatus::Planned,
                'first_team_id'     => $this->firstTeam->id,
                'second_team_id'    => $this->secondTeam->id,
                'first_team_goals'  => null,
                'second_team_goals' => null,
                'week'              => 2,
            ],
        );
    }

    public function testPlayNextWeek(): void
    {
        $response = $this->post(route('fixtures.play-all'));
        $response->assertOk();

        Event::assertDispatched(WeekPlayed::class, 2);

        $response->assertJson(
            [
                'data' => [
                    [
                        "team_id"          => $this->firstTeam->id,
                        "team_name"        => $this->firstTeam->name,
                        "played"           => 2,
                        "won"              => 2,
                        "draw"             => 0,
                        "lost"             => 0,
                        "goals_for"        => 2,
                        "goals_against"    => 0,
                        "goals_difference" => 2,
                        "points"           => 6,
                    ],
                    [
                        "team_id"          => $this->secondTeam->id,
                        "team_name"        => $this->secondTeam->name,
                        "played"           => 2,
                        "won"              => 0,
                        "draw"             => 0,
                        "lost"             => 2,
                        "goals_for"        => 0,
                        "goals_against"    => 2,
                        "goals_difference" => -2,
                        "points"           => 0,
                    ],
                ],
            ],
        );
    }
}
