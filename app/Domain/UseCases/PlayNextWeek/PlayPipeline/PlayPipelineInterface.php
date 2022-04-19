<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek\PlayPipeline;

use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Dto\PipelineDto;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Handlers\PlayResultHandlerInterface;
use App\Domain\ValueObjects\FixturePlayResult\FixturePlayResult;

interface PlayPipelineInterface
{
    public function setHandlers(PlayResultHandlerInterface ...$handlers): self;

    public function start(PipelineDto $pipelineDto): FixturePlayResult;
}
