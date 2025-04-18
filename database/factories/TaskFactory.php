<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(1),
            'status' => fake()->randomElement(['pending', 'doing', 'done']),
            'due_date' => optional(fake()->optional()->dateTimeBetween('now', '+1 month'))->format('Y-m-d'),
            // 'project_id' sera défini depuis le seeder
        ];
    }
}
