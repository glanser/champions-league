<?php

namespace Database\Factories;

use App\Domain\Entities\Fixture\Enum\FixtureStatus;
use App\Domain\Entities\Fixture\Fixture;
use Illuminate\Database\Eloquent\Factories\Factory;

class FixtureFactory extends Factory
{
    protected $model = Fixture::class;

    public function definition(): array
    {
        return [
            'week'              => rand(1, 4),
            'first_team_id'     => rand(1, 999),
            'second_team_id'    => rand(1, 999),
            'first_team_goals'  => 0,
            'second_team_goals' => 0,
            'won_team_id'       => null,
            'status'            => $this->faker->randomElement(FixtureStatus::cases()),
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }
}
