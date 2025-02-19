<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $user = User::factory()->create();

        return [
            'email' => $user->email,
            'status' => fake()->randomElement(['pending', 'accepted', 'declined', 'revoked']),
            'user_id' => User::factory(),
            'board_id' => Board::factory(),
        ];
    }
}
