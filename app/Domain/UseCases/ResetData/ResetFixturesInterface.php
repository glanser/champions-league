<?php

declare(strict_types=1);

namespace App\Domain\UseCases\ResetData;

interface ResetFixturesInterface
{
    public function execute(): void;
}
