<?php

declare(strict_types=1);

namespace App\Http\Resources\Team;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TeamsCollection extends ResourceCollection
{
    public $collects = TeamResource::class;
}
