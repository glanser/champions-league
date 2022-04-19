<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use Exception;

class DomainException extends Exception
{
    protected $message = "Domain exception";
}
