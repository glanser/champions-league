<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek\PlayPipeline\Dto;

use App\Domain\Entities\Fixture\Fixture;

final class PipelineDto
{
    public function __construct(
        public readonly Fixture $currentFixture,
    ) {
    }
}
