<?php

declare(strict_types=1);

namespace App\Domain\UseCases\MakePrediction\PredictionPipeline;

use App\Domain\UseCases\MakePrediction\PredictionPipeline\Dto\PipelineDto;
use App\Domain\UseCases\MakePrediction\PredictionPipeline\Handlers\PredictionHandlerInterface;
use App\Domain\ValueObjects\FixturePlayPrediction\FixturePlayPrediction;
use Closure;

class PredictionPipeline implements PredictionPipelineInterface
{
    private array $handlers = [];

    public function setHandlers(PredictionHandlerInterface ...$handlers): self
    {
        $this->handlers = $handlers;

        return $this;
    }

    public function start(PipelineDto $pipelineDto): FixturePlayPrediction
    {
        $fixturePlayPrediction = new FixturePlayPrediction($pipelineDto->currentFixture);

        $next = $this->makePipe(
            static fn(PipelineDto $pipelineDto, FixturePlayPrediction $fixturePlayResult) => $fixturePlayPrediction
        );

        $next($pipelineDto, $fixturePlayPrediction);

        return $fixturePlayPrediction;
    }

    private function makePipe(Closure $next): Closure
    {
        foreach (array_values($this->handlers) as $index => $value) {
            /** @var PredictionHandlerInterface $handler */
            $handler = $this->handlers[$index];
            $next    = static function (
                PipelineDto $pipelineDto,
                FixturePlayPrediction $fixturePlayPrediction,
            ) use (
                $handler,
                $next,
            ) {
                $handler->handle($pipelineDto, $fixturePlayPrediction, $next);
            };
        }

        return $next;
    }
}
