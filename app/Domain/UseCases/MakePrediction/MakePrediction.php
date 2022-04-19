<?php

declare(strict_types=1);

namespace App\Domain\UseCases\MakePrediction;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\Entities\Fixture\Fixture;
use App\Domain\Enum\CacheKey;
use App\Domain\UseCases\CalculateResults\CalculateResultsInterface;
use App\Domain\UseCases\MakePrediction\Data\MakePredictionOutput;
use App\Domain\UseCases\MakePrediction\PredictionPipeline\Dto\PipelineDto;
use App\Domain\UseCases\MakePrediction\PredictionPipeline\PredictionPipelineInterface;
use App\Domain\ValueObjects\PlayPredictions\PlayPredictions;
use App\Domain\ValueObjects\PlayResults\PlayResults;
use Illuminate\Support\Collection;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class MakePrediction implements MakePredictionInterface
{
    public function __construct(
        private PredictionPipelineInterface $pipeline,
        private CalculateResultsInterface $calculateResultsUseCase,
        private CacheInterface $cache,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function execute(bool $recalculate = false): MakePredictionOutput
    {
        if (!$recalculate && $this->cache->has(CacheKey::Predictions->value)) {
            $cachedData  = json_decode($this->cache->get(CacheKey::Predictions->value), true);
            $predictions = PlayPredictions::deserialize($cachedData);
        } else {
            $availableFixtures = Fixture::where('status', '=', FixtureStatus::Planned)
                ->orderBy('week')->get();
            $currentResults    = $this->calculateResultsUseCase->execute()->playResults;

            $predictions = $this->predict($availableFixtures, $currentResults);

            if (count($predictions) !== 0) {
                $this->cache->set(CacheKey::Predictions->value, json_encode($predictions->getIterator()));
            }
        }

        return new MakePredictionOutput($predictions);
    }

    private function predict(Collection $availableFixtures, PlayResults $playResults): PlayPredictions
    {
        $predictions = PlayPredictions::make($playResults);


        foreach ($availableFixtures as $fixture) {
            $fixturePlayPrediction = $this->pipeline->start(new PipelineDto($fixture, $predictions));
            $predictions->update($fixturePlayPrediction);
        }

        return $predictions;
    }
}
