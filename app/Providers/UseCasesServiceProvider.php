<?php

namespace App\Providers;

use App\Domain\UseCases\GenerateFixtures\GenerateFixtures;
use App\Domain\UseCases\GenerateFixtures\GenerateFixturesInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class UseCasesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
