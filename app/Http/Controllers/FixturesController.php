<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Entities\Fixture\Fixture;
use App\Domain\UseCases\GenerateFixtures\Exceptions\AlreadyGeneratedFixturesException;
use App\Domain\UseCases\GenerateFixtures\Exceptions\IncorrectNumberOfTeamsException;
use App\Domain\UseCases\GenerateFixtures\GenerateFixturesInterface;
use App\Domain\UseCases\PlayAllWeeks\PlayAllWeeksInterface;
use App\Domain\UseCases\PlayNextWeek\Exceptions\WeeksAlreadyPlayedException;
use App\Domain\UseCases\PlayNextWeek\PlayNextWeekInterface;
use App\Domain\UseCases\ResetData\ResetFixturesInterface;
use App\Http\Resources\Fixture\FixturesCollection;
use App\Http\Resources\Result\ResultsCollection;
use App\Http\Resources\SuccessResource;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class FixturesController extends Controller
{
    /**
     * @throws IncorrectNumberOfTeamsException
     */
    public function generateFixtures(GenerateFixturesInterface $generateFixturesUseCase): FixturesCollection
    {
        try {
            $fixtures = $generateFixturesUseCase->execute()->fixtures;
        } catch (AlreadyGeneratedFixturesException) {
            throw new ConflictHttpException('Fixtures already generated');
        }

        return FixturesCollection::make($fixtures);
    }

    public function getFixtures(): FixturesCollection
    {
        return FixturesCollection::make(Fixture::all());
    }

    public function playNextWeek(PlayNextWeekInterface $playNextWeekUseCase): FixturesCollection
    {
        try {
            $output = $playNextWeekUseCase->execute();
        } catch (WeeksAlreadyPlayedException) {
            throw new ConflictHttpException('Weeks already played');
        }

        return FixturesCollection::make($output->fixtures);
    }

    public function playAllWeeks(PlayAllWeeksInterface $playAllWeeksUseCase): ResultsCollection
    {
        $results = $playAllWeeksUseCase->execute()->playResults;

        return ResultsCollection::make(collect($results)->values());
    }

    public function resetFixtures(ResetFixturesInterface $resetFixturesUseCase): SuccessResource
    {
        $resetFixturesUseCase->execute();

        return SuccessResource::make(null);
    }
}
