<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date,
            'published' => $this->faker->boolean,
            'metadata' => json_encode([
                'keywords' => $this->faker->words(5, true),
                'duration' => $this->faker->numberBetween(60, 600),
            ]),
            'category' => $this->faker->word,
            'slug' => $this->faker->slug,
            'youtubeURL' => 'https://www.youtube.com/watch?v=' . $this->faker->uuid,
            'views' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
