<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\UseCases\CalculateResults\CalculateResultsInterface;
use App\Http\Resources\Result\ResultsCollection;

class ResultsController extends Controller
{
    public function getResults(CalculateResultsInterface $calculateResultsUseCase): ResultsCollection
    {
        $results = $calculateResultsUseCase->execute()->playResults;

        return ResultsCollection::make(collect($results)->values());
    }
}
