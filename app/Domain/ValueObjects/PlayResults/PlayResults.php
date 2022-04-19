<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\PlayResults;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\Entities\Fixture\Fixture;
use App\Domain\Entities\Team\Team;
use ArrayIterator;
use Countable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Traversable;

final class PlayResults implements IteratorAggregate, Countable
{
    /**
     * @var array<PlayResult>
     */
    private array $items = [];

    public static function deserialize(array $data): self
    {
        $playResults = new self();

        foreach ($data as $playResultData) {
            $playResults->add(
                new PlayResult(
                    teamId:       $playResultData['team_id'],
                    teamName:     $playResultData['team_name'],
                    played:       $playResultData['played'],
                    won:          $playResultData['won'],
                    draw:         $playResultData['draw'],
                    lost:         $playResultData['lost'],
                    goalsFor:     $playResultData['goals_for'],
                    goalsAgainst: $playResultData['goals_against'],
                )
            );
        }

        return $playResults;
    }

    /**
     * @param Collection<Fixture> $fixtures
     *
     * @return PlayResults
     */
    public static function make(Collection $fixtures): self
    {
        $playResults = new self();

        $teams = $fixtures->flatMap(fn(Fixture $fixture) => [$fixture->firstTeam, $fixture->secondTeam])
            ->flatten()
            ->unique('id');

        $playResults->initTeams($teams);
        $playResults->calculateResults($fixtures);


        return $playResults;
    }

    /**
     * @param Collection<Team> $teams
     *
     * @return void
     */
    private function initTeams(Collection $teams): void
    {
        /** @var Team $team */
        foreach ($teams as $team) {
            $this->add(
                new PlayResult(
                    teamId:       $team->id,
                    teamName:     $team->name,
                    played:       0,
                    won:          0,
                    draw:         0,
                    lost:         0,
                    goalsFor:     0,
                    goalsAgainst: 0
                )
            );
        }
    }

    private function calculateResults(Collection $fixtures): void
    {
        /** @var Fixture $fixture */
        foreach ($fixtures as $fixture) {
            if ($fixture->status !== FixtureStatus::Played) {
                continue;
            }

            $this->items[$fixture->first_team_id]->update($fixture->first_team_goals, $fixture->second_team_goals);
            $this->items[$fixture->second_team_id]->update($fixture->second_team_goals, $fixture->first_team_goals);
        }
    }

    private function add(PlayResult $playResult): void
    {
        $this->items[$playResult->getTeamId()] = $playResult;
    }

    /**
     * @return Traversable<PlayResult>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
