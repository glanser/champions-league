<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayAllWeeks;

use App\Domain\UseCases\CalculateResults\CalculateResultsInterface;
use App\Domain\UseCases\PlayAllWeeks\Data\PlayAllWeeksOutput;
use App\Domain\UseCases\PlayNextWeek\Exceptions\WeeksAlreadyPlayedException;
use App\Domain\UseCases\PlayNextWeek\PlayNextWeekInterface;

class PlayAllWeeks implements PlayAllWeeksInterface
{
    public function __construct(
        private PlayNextWeekInterface $playNextWeekUseCase,
        private CalculateResultsInterface $calculateResultsUseCase,
    ) {
    }

    public function execute(): PlayAllWeeksOutput
    {
        $this->play();

        return new PlayAllWeeksOutput($this->calculateResultsUseCase->execute()->playResults);
    }

    public function play(): void
    {
        try {
            $this->playNextWeekUseCase->execute();
            $this->play();
        } catch (WeeksAlreadyPlayedException) {
            return;
        }
    }
}
