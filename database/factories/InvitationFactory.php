<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Board;
use App\Models\Invitation;
use App\Models\User;

class InvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'email' => fake()->safeEmail(),
            'status' => fake()->randomElement(["pending","accepted","declined"]),
            'user_id' => User::factory(),
            'board_id' => Board::factory(),
        ];
    }
}
