<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek;

use App\Domain\UseCases\PlayNextWeek\Data\PlayNextWeekOutput;
use App\Domain\UseCases\PlayNextWeek\Exceptions\WeeksAlreadyPlayedException;

interface PlayNextWeekInterface
{
    /**
     * @throws WeeksAlreadyPlayedException
     */
    public function execute(): PlayNextWeekOutput;
}
