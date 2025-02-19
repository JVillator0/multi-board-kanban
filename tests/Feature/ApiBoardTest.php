<?php

use App\Models\Board;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

test('a user can reorder boards', function () {
    $boards = Board::factory()->count(3)->create(['user_id' => $this->user->id]);

    $data = [
        'boards' => $boards->map(fn ($board, $index) => ['id' => $board->id, 'order' => $index])->toArray(),
    ];

    $this->postJson(route('api.boards.reorder'), $data)
        ->assertOk()
        ->assertJson(['message' => 'Boards reordered successfully.']);

    foreach ($data['boards'] as $board) {
        $this->assertDatabaseHas('boards', ['id' => $board['id'], 'order' => $board['order']]);
    }
});
