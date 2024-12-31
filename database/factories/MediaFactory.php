<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $extensions = ['jpg', 'jpeg', 'png'];

        return [
            'id' => $this->faker->unique()->randomNumber(),
            'project_id' => Project::inRandomOrder()->first()->id,
            'path' => $this->faker->filePath(),
            'file_name' => $this->faker->word() . '.' . $this->faker->randomElement($extensions),
            'mime_type' => $this->faker->mimeType(),
            'size' => $this->faker->numberBetween(1000, 10000000),
        ];
    }
}
