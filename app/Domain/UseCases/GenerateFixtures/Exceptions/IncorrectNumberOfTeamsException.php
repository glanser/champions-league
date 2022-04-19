<?php

declare(strict_types=1);

namespace App\Domain\UseCases\GenerateFixtures\Exceptions;

use App\Domain\Exceptions\DomainException;

class IncorrectNumberOfTeamsException extends DomainException
{
    protected $message = "Incorrect number of teams exception";
}
