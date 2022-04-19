<?php

use App\Http\Controllers\FixturesController;
use App\Http\Controllers\PredictionsController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\TeamsController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => '/v1'],
    static function () {
        Route::get('teams', [TeamsController::class, 'getTeams'])->name('teams.get');

        Route::get('fixtures', [FixturesController::class, 'getFixtures'])->name('fixtures.get');
        Route::post('fixtures/generate', [FixturesController::class, 'generateFixtures'])->name('fixtures.generate');
        Route::post('fixtures/play-next', [FixturesController::class, 'playNextWeek'])->name('fixtures.play-next');
        Route::post('fixtures/play-all', [FixturesController::class, 'playAllWeeks'])->name('fixtures.play-all');
        Route::post('fixtures/reset', [FixturesController::class, 'resetFixtures'])->name('fixtures.reset');

        Route::get('results', [ResultsController::class, 'getResults'])->name('results.get');
        Route::get('predictions', [PredictionsController::class, 'getPredictions'])->name('predictions.get');
    }
);
