<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek\PlayPipeline\Handlers;

use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Dto\PipelineDto;
use App\Domain\ValueObjects\FixturePlayResult\FixturePlayResult;
use Closure;

class RandomPlayResultHandler implements PlayResultHandlerInterface
{
    public function __construct(
        private float $weight,
        private int $min,
        private int $max,
    ) {
    }

    public function handle(
        PipelineDto $pipelineDto,
        FixturePlayResult $fixturePlayResult,
        Closure $next
    ): void {
        $fixturePlayResult->modify(
            rand($this->min, $this->max),
            rand($this->min, $this->max),
            $this->weight,
        );

        $next($pipelineDto, $fixturePlayResult);
    }
}
