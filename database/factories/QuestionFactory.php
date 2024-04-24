<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question'   => fake()->realText(50) . '?',
            'draft'      => fake()->boolean(),
            'created_by' => User::factory(),
        ];
    }
}
