<?php

namespace App\Providers;

use App\Domain\UseCases\CalculateResults\CalculateResults;
use App\Domain\UseCases\CalculateResults\CalculateResultsInterface;
use App\Domain\UseCases\GenerateFixtures\GenerateFixtures;
use App\Domain\UseCases\GenerateFixtures\GenerateFixturesInterface;
use App\Domain\UseCases\MakePrediction\MakePrediction;
use App\Domain\UseCases\MakePrediction\MakePredictionInterface;
use App\Domain\UseCases\MakePrediction\PredictionPipeline\PredictionPipeline;
use App\Domain\UseCases\MakePrediction\PredictionPipeline\PredictionPipelineInterface;
use App\Domain\UseCases\PlayAllWeeks\PlayAllWeeks;
use App\Domain\UseCases\PlayAllWeeks\PlayAllWeeksInterface;
use App\Domain\UseCases\PlayNextWeek\PlayNextWeek;
use App\Domain\UseCases\PlayNextWeek\PlayNextWeekInterface;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\PlayPipeline;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\PlayPipelineInterface;
use App\Domain\UseCases\ResetData\ResetFixtures;
use App\Domain\UseCases\ResetData\ResetFixturesInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            GenerateFixturesInterface::class,
            static fn(Application $app) => $app->makeWith(
                GenerateFixtures::class,
                [
                    'minNumberOfTeams' => config('fixtures.min_number_of_teams'),
                    'weeks'            => config('fixtures.weeks'),
                ]
            )
        );

        $this->app->singleton(
            PlayPipelineInterface::class,
            static function (Application $app) {
                $handlers     = config('pipelines.play');
                $weightAmount = array_sum(array_map(static fn($handler) => $handler['options']['weight'], $handlers));
                $handlers     = array_map(
                    static function ($handler) use ($weightAmount, $app) {
                        $handler['options']['weight'] = $handler['options']['weight'] / $weightAmount;

                        return $app->makeWith($handler['handler'], $handler['options']);
                    },
                    $handlers
                );

                $pipeline = $app->make(PlayPipeline::class);
                $pipeline->setHandlers(...$handlers);

                return $pipeline;
            }
        );

        $this->app->singleton(
            PredictionPipelineInterface::class,
            static function (Application $app) {
                $handlers     = config('pipelines.prediction');
                $weightAmount = array_sum(array_map(static fn($handler) => $handler['options']['weight'], $handlers));
                $handlers     = array_map(
                    static function ($handler) use ($weightAmount, $app) {
                        $handler['options']['weight'] = $handler['options']['weight'] / $weightAmount;

                        return $app->makeWith($handler['handler'], $handler['options']);
                    },
                    $handlers
                );

                $pipeline = $app->make(PredictionPipeline::class);
                $pipeline->setHandlers(...$handlers);

                return $pipeline;
            }
        );

        $this->app->bind(PlayNextWeekInterface::class, PlayNextWeek::class);
        $this->app->bind(PlayAllWeeksInterface::class, PlayAllWeeks::class);
        $this->app->bind(CalculateResultsInterface::class, CalculateResults::class);
        $this->app->bind(MakePredictionInterface::class, MakePrediction::class);
        $this->app->bind(ResetFixturesInterface::class, ResetFixtures::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
