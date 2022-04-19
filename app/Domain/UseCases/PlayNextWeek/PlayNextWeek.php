<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\Entities\Fixture\Fixture;
use App\Domain\Events\WeekPlayed;
use App\Domain\UseCases\PlayNextWeek\Data\PlayNextWeekOutput;
use App\Domain\UseCases\PlayNextWeek\Exceptions\WeeksAlreadyPlayedException;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Dto\PipelineDto;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\PlayPipelineInterface;

class PlayNextWeek implements PlayNextWeekInterface
{
    public function __construct(
        private PlayPipelineInterface $pipeline,
    ) {
    }

    /**
     * @throws WeeksAlreadyPlayedException
     */
    public function execute(): PlayNextWeekOutput
    {
        $availableFixtures = Fixture::where('status', '=', FixtureStatus::Planned)
            ->orderBy('week')->get();

        if ($availableFixtures->isEmpty()) {
            throw new WeeksAlreadyPlayedException();
        }

        $nextFixtures = $availableFixtures->groupBy('week')->first();

        /** @var Fixture $fixture */
        foreach ($nextFixtures as $fixture) {
            $fixturePlayResult          = $this->pipeline->start(new PipelineDto($fixture));
            $fixture->first_team_goals  = $fixturePlayResult->getFirstTeamGoals();
            $fixture->second_team_goals = $fixturePlayResult->getSecondTeamGoals();
            $fixture->status            = FixtureStatus::Played;

            if ($fixture->first_team_goals > $fixture->second_team_goals) {
                $fixture->won_team_id = $fixture->firstTeam->id;
            }

            if ($fixture->second_team_goals > $fixture->first_team_goals) {
                $fixture->won_team_id = $fixture->secondTeam->id;
            }

            $fixture->save();
        }

        WeekPlayed::dispatch();

        return new PlayNextWeekOutput($nextFixtures);
    }
}
