<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek\PlayPipeline\Handlers;

use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Dto\PipelineDto;
use App\Domain\ValueObjects\FixturePlayResult\FixturePlayResult;
use Closure;

interface PlayResultHandlerInterface
{
    public function handle(PipelineDto $pipelineDto, FixturePlayResult $fixturePlayResult, Closure $next): void;
}
