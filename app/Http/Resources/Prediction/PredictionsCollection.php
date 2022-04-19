<?php

declare(strict_types=1);

namespace App\Http\Resources\Prediction;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PredictionsCollection extends ResourceCollection
{
    public $collects = PredictionResource::class;
}
