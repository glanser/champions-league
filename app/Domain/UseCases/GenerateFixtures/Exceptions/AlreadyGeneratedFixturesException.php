<?php

declare(strict_types=1);

namespace App\Domain\UseCases\GenerateFixtures\Exceptions;

use App\Domain\Exceptions\DomainException;

class AlreadyGeneratedFixturesException extends DomainException
{
    protected $message = "Fixtures already generated exception";
}
