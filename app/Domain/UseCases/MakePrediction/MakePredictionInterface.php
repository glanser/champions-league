<?php

declare(strict_types=1);

namespace App\Domain\UseCases\MakePrediction;

use App\Domain\UseCases\MakePrediction\Data\MakePredictionOutput;

interface MakePredictionInterface
{
    public function execute(bool $recalculate = false): MakePredictionOutput;
}
