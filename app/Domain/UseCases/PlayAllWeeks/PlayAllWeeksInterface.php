<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayAllWeeks;

use App\Domain\UseCases\PlayAllWeeks\Data\PlayAllWeeksOutput;

interface PlayAllWeeksInterface
{
    public function execute(): PlayAllWeeksOutput;
}
