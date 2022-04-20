<?php

declare(strict_types=1);

namespace App\Domain\UseCases\GenerateFixtures;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\Entities\Fixture\Fixture;
use App\Domain\Entities\Team\Team;
use App\Domain\UseCases\GenerateFixtures\Data\GenerateFixturesOutput;
use App\Domain\UseCases\GenerateFixtures\Exceptions\AlreadyGeneratedFixturesException;
use App\Domain\UseCases\GenerateFixtures\Exceptions\IncorrectNumberOfTeamsException;
use Illuminate\Support\Collection;

class GenerateFixtures implements GenerateFixturesInterface
{
    public function __construct(
        private int $minNumberOfTeams,
        private int $weeks,
    ) {
    }

    /**
     * @throws IncorrectNumberOfTeamsException
     * @throws AlreadyGeneratedFixturesException
     */
    public function execute(): GenerateFixturesOutput
    {
        if (Fixture::count() > 0) {
            throw new AlreadyGeneratedFixturesException();
        }

        $teams = Team::all();
        if ($teams->count() < $this->minNumberOfTeams) {
            throw new IncorrectNumberOfTeamsException();
        }

        $pairsByWeeks = $this->getPairsByWeeks($teams, $this->weeks);

        $fixtures = collect();
        foreach ($pairsByWeeks as $weekKey => $pairs) {
            /** @var array<Team> $pair */
            foreach ($pairs as $pair) {
                $fixture         = new Fixture();
                $fixture->week   = $weekKey + 1;
                $fixture->status = FixtureStatus::Planned;
                $fixture->firstTeam()->associate($pair[0]);
                $fixture->secondTeam()->associate($pair[1]);
                $fixture->save();

                $fixtures->add($fixture);
            }
        }

        return new GenerateFixturesOutput($fixtures);
    }

    private function getPairsByWeeks(Collection $teams, int $weeks): Collection
    {
        $teamPairs = collect([]);

        foreach ($teams as $firstTeam) {
            foreach ($teams as $secondTeam) {
                if ($firstTeam === $secondTeam || $teamPairs->contains([$firstTeam, $secondTeam])) {
                    continue;
                }

                $teamPairs->add([$firstTeam, $secondTeam]);
            }
        }

        $weekSections    = collect([]);
        $pairsPerSection = ceil($teamPairs->count() / $weeks);

        for ($i = 0; $i < $weeks; $i++) {
            $pairs     = collect([]);
            $firstPair = $teamPairs->first();
            $pairs->add($firstPair);
            $usedTeams = $pairs->flatten();

            for ($j = 0; $j < $pairsPerSection - 1; $j++) {
                $pairsFiltered = $teamPairs->filter(
                    static fn(array $pair) => !($usedTeams->contains($pair[0]) || $usedTeams->contains($pair[1]))
                );

                if ($pairsFiltered->isEmpty()) {
                    continue;
                }

                $nextPair = $pairsFiltered->first();
                $pairs->add($nextPair);
                $usedTeams = $pairs->flatten();
            }

            $weekSections->add($pairs);

            $teamPairs = $teamPairs->filter(static fn(array $pair) => !$pairs->contains($pair));
        }

        return $weekSections;
    }
}
