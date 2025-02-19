<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'order' => fake()->numberBetween(-10000, 10000),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'status' => fake()->randomElement(['backlog', 'todo', 'in_progress', 'done']),
            'due_date' => fake()->dateTime(),
            'board_id' => Board::factory(),
            'user_id' => User::factory(),
        ];
    }
}
