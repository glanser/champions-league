<?php

namespace App\Providers;

use App\Domain\Events\WeekPlayed;
use App\Domain\Listeners\ClearPredictionsCache;
use App\Domain\Listeners\RecalculateResults;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        WeekPlayed::class => [
            ClearPredictionsCache::class,
            RecalculateResults::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
