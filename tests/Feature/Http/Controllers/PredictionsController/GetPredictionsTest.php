<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\PredictionsController;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\UseCases\MakePrediction\MakePrediction;
use App\Domain\UseCases\MakePrediction\MakePredictionInterface;
use App\Domain\UseCases\MakePrediction\PredictionPipeline\Dto\PipelineDto;
use App\Domain\UseCases\MakePrediction\PredictionPipeline\Handlers\PredictionHandlerInterface;
use App\Domain\UseCases\MakePrediction\PredictionPipeline\PredictionPipeline;
use App\Domain\ValueObjects\FixturePlayPrediction\FixturePlayPrediction;
use Closure;
use Database\Factories\FixtureFactory;
use Database\Factories\TeamFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetPredictionsTest extends TestCase
{
    use RefreshDatabase;

    private array $expectedPredictions;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $handler = new class implements PredictionHandlerInterface {
            public function handle(
                PipelineDto $pipelineDto,
                FixturePlayPrediction $fixturePlayPrediction,
                Closure $next
            ): void {
                $fixturePlayPrediction->modify(1, 0, 1);
            }
        };

        /** @var PredictionPipeline $pipeline */
        $pipeline = $this->app->make(PredictionPipeline::class);
        $pipeline->setHandlers($handler);

        $this->app->instance(
            MakePredictionInterface::class,
            $this->app->makeWith(
                MakePrediction::class,
                ['pipeline' => $pipeline]
            )
        );

        $firstTeam  = TeamFactory::new()->create();
        $secondTeam = TeamFactory::new()->create();

        FixtureFactory::new()->create(
            [
                'status'            => FixtureStatus::Planned,
                'first_team_id'     => $firstTeam->id,
                'second_team_id'    => $secondTeam->id,
                'first_team_goals'  => null,
                'second_team_goals' => null,
                'week'              => 1,
            ],
        );

        FixtureFactory::new()->create(
            [
                'status'            => FixtureStatus::Planned,
                'first_team_id'     => $firstTeam->id,
                'second_team_id'    => $secondTeam->id,
                'first_team_goals'  => null,
                'second_team_goals' => null,
                'week'              => 1,
            ],
        );

        FixtureFactory::new()->create(
            [
                'status'            => FixtureStatus::Planned,
                'first_team_id'     => $firstTeam->id,
                'second_team_id'    => $secondTeam->id,
                'first_team_goals'  => null,
                'second_team_goals' => null,
                'week'              => 2,
            ],
        );

        $this->expectedPredictions = [
            [
                "team_id"          => $firstTeam->id,
                "team_name"        => $firstTeam->name,
                "played"           => 3,
                "won"              => 3,
                "draw"             => 0,
                "lost"             => 0,
                "goals_for"        => 3,
                "goals_against"    => 0,
                "goals_difference" => 3,
                "points"           => 9,
            ],
            [
                "team_id"          => $secondTeam->id,
                "team_name"        => $secondTeam->name,
                "played"           => 3,
                "won"              => 0,
                "draw"             => 0,
                "lost"             => 3,
                "goals_for"        => 0,
                "goals_against"    => 3,
                "goals_difference" => -3,
                "points"           => 0,
            ],
        ];
    }

    public function testGetPredictions(): void
    {
        $response = $this->get(route('predictions.get'));
        $response->assertOk();

        $response->assertJson(['data' => $this->expectedPredictions]);
    }
}
