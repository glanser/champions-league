<?php

use App\Domain\UseCases\MakePrediction\PredictionPipeline\Handlers\InitialPointsPredictionHandler;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Handlers\InitialPointsPlayResultHandler;
use App\Domain\UseCases\PlayNextWeek\PlayPipeline\Handlers\RandomPlayResultHandler;

return [
    'play' => [
        [
            'handler' => RandomPlayResultHandler::class,
            'options' => [
                'weight' => 100,
                'min'    => 0,
                'max'    => 5,
            ],
        ],
        [
            'handler' => InitialPointsPlayResultHandler::class,
            'options' => [
                'weight' => 500,
                'min'    => 0,
                'max'    => 7,
            ],
        ],
    ],

    'prediction' => [
        [
            'handler' => InitialPointsPredictionHandler::class,
            'options' => [
                'weight' => 100,
                'min'    => 0,
                'max'    => 7,
            ],
        ],
    ],
];
