<?php

use App\Models\Board;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
    $this->board = Board::factory()->create(['user_id' => $this->user->id]);
});

test('a user can reorder tasks', function () {
    $tasks = Task::factory()->count(3)->create(['board_id' => $this->board->id]);

    $data = [
        'tasks' => $tasks->map(fn ($task, $index) => ['id' => $task->id, 'order' => $index])->toArray(),
    ];

    $this->postJson(route('api.tasks.reorder'), $data)
        ->assertOk()
        ->assertJson(['message' => 'Tasks reordered successfully.']);

    foreach ($data['tasks'] as $task) {
        $this->assertDatabaseHas('tasks', ['id' => $task['id'], 'order' => $task['order']]);
    }
});
