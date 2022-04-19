<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\FixturesController;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use Database\Factories\FixtureFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\SimpleCache\CacheInterface;
use Tests\TestCase;

class ResetFixturesTest extends TestCase
{
    use RefreshDatabase;

    private MockObject | CacheInterface $cacheMock;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->cacheMock = $this->createMock(CacheInterface::class);
        $this->app->instance(CacheInterface::class, $this->cacheMock);
    }

    public function testResetFixtures(): void
    {
        $this->cacheMock->expects($this->exactly(2))->method('delete');

        $response = $this->post(route('fixtures.reset'));
        $response->assertOk();

        $this->assertDatabaseCount('fixtures', 0);
    }
}
