<?php

declare(strict_types=1);

namespace App\Http\Resources\Result;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ResultsCollection extends ResourceCollection
{
    public $collects = ResultResource::class;
}
