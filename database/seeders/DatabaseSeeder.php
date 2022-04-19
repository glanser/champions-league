<?php

namespace Database\Seeders;

use Database\Factories\TeamFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public array $teams = [
        [
            'name' => 'Manchester City',
            'played' => 29,
            'won' => 22,
            'draw' => 5,
            'lost' => 3,
            'goal_difference' => 50
        ],
        [
            'name' => 'Liverpool',
            'played' => 29,
            'won' => 21,
            'draw' => 6,
            'lost' => 2,
            'goal_difference' => 55
        ],
        [
            'name' => 'Chelsea',
            'played' => 28,
            'won' => 17,
            'draw' => 8,
            'lost' => 3,
            'goal_difference' => 38
        ],
        [
            'name' => 'Arsenal',
            'played' => 28,
            'won' => 17,
            'draw' => 3,
            'lost' => 8,
            'goal_difference' => 13
        ],
    ];

    public function run()
    {
        foreach ($this->teams as $team) {
            TeamFactory::new()->create($team);
        }
    }
}
