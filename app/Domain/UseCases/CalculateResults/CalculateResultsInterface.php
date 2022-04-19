<?php

declare(strict_types=1);

namespace App\Domain\UseCases\CalculateResults;

use App\Domain\UseCases\CalculateResults\Data\CalculateResultsOutput;

interface CalculateResultsInterface
{
    public function execute(bool $recalculate = false): CalculateResultsOutput;
}
