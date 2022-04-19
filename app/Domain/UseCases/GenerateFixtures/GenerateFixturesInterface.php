<?php

declare(strict_types=1);

namespace App\Domain\UseCases\GenerateFixtures;

use App\Domain\UseCases\GenerateFixtures\Data\GenerateFixturesOutput;
use App\Domain\UseCases\GenerateFixtures\Exceptions\AlreadyGeneratedFixturesException;
use App\Domain\UseCases\GenerateFixtures\Exceptions\IncorrectNumberOfTeamsException;

interface GenerateFixturesInterface
{
    /**
     * @throws IncorrectNumberOfTeamsException
     * @throws AlreadyGeneratedFixturesException
     */
    public function execute(): GenerateFixturesOutput;
}
