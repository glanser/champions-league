<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\UseCases\MakePrediction\MakePredictionInterface;
use App\Http\Resources\Prediction\PredictionsCollection;

class PredictionsController extends Controller
{
    public function getPredictions(MakePredictionInterface $makePredictionUseCase)
    {
        $playPredictions = $makePredictionUseCase->execute()->playPredictions;

        return PredictionsCollection::make(collect($playPredictions)->values());
    }
}
