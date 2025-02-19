<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Invitation;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function ($user) {
            $boards = Board::factory(rand(3, 5))->create(['user_id' => $user->id]);

            foreach ($boards as $board) {
                $availableUsers = User::where('id', '!=', $user->id)->get();

                $guests = $availableUsers->random(3);

                foreach ($guests as $guest) {
                    Invitation::factory()->create([
                        'email' => $guest->email,
                        'status' => 'accepted',
                        'user_id' => $user->id,
                        'board_id' => $board->id,
                    ]);
                }

                Task::factory(rand(5, 10))->create([
                    'board_id' => $board->id,
                    'assigned_user_id' => fake()->randomElement([$user->id, ...$guests->pluck('id')->toArray()]),
                ]);
            }
        });

        User::where('id', 1)->update([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
