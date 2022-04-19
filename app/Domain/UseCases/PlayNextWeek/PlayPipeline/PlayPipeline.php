<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek\PlayPipeline;

use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Dto\PipelineDto;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Handlers\PlayResultHandlerInterface;
use App\Domain\ValueObjects\FixturePlayResult\FixturePlayResult;
use Closure;

class PlayPipeline implements PlayPipelineInterface
{
    private array $handlers = [];

    public function setHandlers(PlayResultHandlerInterface ...$handlers): self
    {
        $this->handlers = $handlers;

        return $this;
    }

    public function start(PipelineDto $pipelineDto): FixturePlayResult
    {
        $fixturePlayResult = new FixturePlayResult();

        $next = $this->makePipe(
            static fn(PipelineDto $pipelineDto, FixturePlayResult $fixturePlayResult) => $fixturePlayResult
        );

        $next($pipelineDto, $fixturePlayResult);

        return $fixturePlayResult;
    }

    private function makePipe(Closure $next): Closure
    {
        foreach (array_values($this->handlers) as $index => $value) {
            /** @var PlayResultHandlerInterface $handler */
            $handler = $this->handlers[$index];
            $next    = static function (
                PipelineDto $pipelineDto,
                FixturePlayResult $fixturePlayResult,
            ) use (
                $handler,
                $next,
            ) {
                $handler->handle($pipelineDto, $fixturePlayResult, $next);
            };
        }

        return $next;
    }
}
