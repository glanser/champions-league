<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PlayNextWeek\Exceptions;

use App\Domain\Exceptions\DomainException;

class WeeksAlreadyPlayedException extends DomainException
{
    protected $message = "Weeks already played exception";
}
