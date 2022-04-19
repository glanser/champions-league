<?php

declare(strict_types=1);

namespace App\Domain\Entities\Fixture\Enum;

enum FixtureStatus: string
{
case Planned = 'planned';
case Played = 'played';
    }
