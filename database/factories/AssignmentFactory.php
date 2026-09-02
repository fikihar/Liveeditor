<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_id' => ClassRoom::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'type' => 'tugas',
            'deadline' => now()->addDays(7),
            'starter_html' => '<h1>Hello</h1>',
            'starter_css' => 'h1 { color: red; }',
            'has_css' => true,
            'max_score' => 100,
            'is_graded' => true,
            'status' => 'published',
        ];
    }
}
