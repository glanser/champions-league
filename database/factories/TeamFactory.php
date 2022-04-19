<?php

namespace Database\Factories;

use App\Domain\Entities\Team\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'name'            => $this->faker->name . ' ' . $this->faker->country,
            'played'          => rand(0, 20),
            'won'             => rand(0, 20),
            'draw'            => rand(0, 20),
            'lost'            => rand(0, 20),
            'goal_difference' => rand(-20, 20),
            'updated_at'      => now(),
            'created_at'      => now(),
        ];
    }
}
