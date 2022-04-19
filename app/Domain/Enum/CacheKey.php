<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum CacheKey: string
{
case Results = 'results';
case Predictions = 'predictions';
    }
