<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tmdb_id' => $this->faker->unique()->numberBetween(1, 999999),
            'title' => $this->faker->sentence(3),
            'overview' => $this->faker->paragraph(),
            'poster_path' => '/poster_' . $this->faker->uuid() . '.jpg',
            'backdrop_path' => '/backdrop_' . $this->faker->uuid() . '.jpg',
            'release_date' => $this->faker->date(),
            'vote_average' => $this->faker->randomFloat(1, 0, 10),
            'vote_count' => $this->faker->numberBetween(0, 10000),
            'popularity' => $this->faker->randomFloat(3, 0, 1000),
            'genre_ids' => $this->faker->randomElements([28, 12, 16, 35, 80, 99, 18, 10751, 14, 36, 27, 10402, 9648, 10749, 878, 10770, 53, 10752, 37], 3),
            'original_language' => 'en',
            'original_title' => $this->faker->sentence(3),
            'adult' => false,
            'video' => false
        ];
    }
} 