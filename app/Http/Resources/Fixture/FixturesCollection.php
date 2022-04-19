<?php

declare(strict_types=1);

namespace App\Http\Resources\Fixture;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FixturesCollection extends ResourceCollection
{
    public $collects = FixtureResource::class;
}
