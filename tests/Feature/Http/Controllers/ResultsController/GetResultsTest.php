<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\ResultsController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Psr\SimpleCache\CacheInterface;
use Tests\TestCase;

class GetResultsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array[]
     */
    private array $results;

    protected function setUp(): void
    {
        parent::setUp();

        $this->results = [
            [
                "team_id"          => 2,
                "team_name"        => "Liverpool",
                "played"           => 6,
                "won"              => 0,
                "draw"             => 5,
                "lost"             => 1,
                "goals_for"        => 3,
                "goals_against"    => 4,
                "goals_difference" => -1,
                "points"           => 5,
            ],
            [
                "team_id"          => 1,
                "team_name"        => "Manchester City",
                "played"           => 6,
                "won"              => 3,
                "draw"             => 3,
                "lost"             => 0,
                "goals_for"        => 5,
                "goals_against"    => 2,
                "goals_difference" => 3,
                "points"           => 12,
            ],
            [
                "team_id"          => 4,
                "team_name"        => "Arsenal",
                "played"           => 6,
                "won"              => 1,
                "draw"             => 5,
                "lost"             => 0,
                "goals_for"        => 6,
                "goals_against"    => 5,
                "goals_difference" => 1,
                "points"           => 8,
            ],
            [
                "team_id"          => 3,
                "team_name"        => "Chelsea",
                "played"           => 6,
                "won"              => 0,
                "draw"             => 3,
                "lost"             => 3,
                "goals_for"        => 2,
                "goals_against"    => 5,
                "goals_difference" => -3,
                "points"           => 3,
            ],
        ];

        $cacheMock = $this->createMock(CacheInterface::class);
        $cacheMock->method('has')->willReturn(true);
        $cacheMock->method('get')->willReturn(json_encode($this->results));

        $this->app->instance(CacheInterface::class, $cacheMock);
    }

    public function testGetResults(): void
    {
        $response = $this->get(route('results.get'));
        $response->assertOk();

        $response->assertJson(['data' => $this->results]);
    }
}
