<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassRoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Kelas ' . $this->faker->word(),
            'description' => $this->faker->sentence(),
            'guru_id' => User::factory()->create(['role' => 'guru'])->id,
        ];
    }
}
