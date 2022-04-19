<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\PlayPredictions;

use App\Domain\ValueObjects\FixturePlayPrediction\FixturePlayPrediction;
use App\Domain\ValueObjects\PlayResults\PlayResult;
use App\Domain\ValueObjects\PlayResults\PlayResults;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class PlayPredictions implements IteratorAggregate, Countable
{
    /**
     * @var array<PlayPrediction>
     */
    private array $items = [];

    public static function deserialize(array $data): self
    {
        $playPredictions = new self();

        foreach ($data as $playPredictionData) {
            $playPredictions->add(
                new PlayPrediction(
                    teamId:       $playPredictionData['team_id'],
                    teamName:     $playPredictionData['team_name'],
                    played:       $playPredictionData['played'],
                    won:          $playPredictionData['won'],
                    draw:         $playPredictionData['draw'],
                    lost:         $playPredictionData['lost'],
                    goalsFor:     $playPredictionData['goals_for'],
                    goalsAgainst: $playPredictionData['goals_against'],
                )
            );
        }

        return $playPredictions;
    }

    public static function make(PlayResults $playResults): self
    {
        $playPredictions = new self();

        /** @var PlayResult $playResult */
        foreach ($playResults as $playResult) {
            $playPredictions->add(PlayPrediction::make($playResult));
        }

        return $playPredictions;
    }

    public function update(FixturePlayPrediction $fixturePlayPrediction): void
    {
        $fixture = $fixturePlayPrediction->fixture;
        $this->items[$fixture->first_team_id]->update(
            $fixturePlayPrediction->getFirstTeamGoals(),
            $fixturePlayPrediction->getSecondTeamGoals(),
        );
        $this->items[$fixture->second_team_id]->update(
            $fixturePlayPrediction->getSecondTeamGoals(),
            $fixturePlayPrediction->getFirstTeamGoals(),
        );
    }

    private function add(PlayPrediction $playPrediction): void
    {
        $this->items[$playPrediction->getTeamId()] = $playPrediction;
    }

    /**
     * @return Traversable<PlayPrediction>
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
