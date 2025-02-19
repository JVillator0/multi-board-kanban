<?php

use App\Models\Board;
use App\Models\Task;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->board = Board::factory()->create(['user_id' => $this->user->id]);
});

test('a user can create a task', function () {
    $data = [
        'title' => 'New Task',
        'description' => 'Test description',
        'status' => 'todo',
        'priority' => 'medium',
        'board_id' => $this->board->id,
    ];

    $this->post(route('tasks.store'), $data)
        ->assertRedirect(route('boards.show', $this->board));

    $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
});

test('a user can update a task', function () {
    $task = Task::factory()->create(['board_id' => $this->board->id]);

    $data = [
        'title' => 'Updated Task',
        'description' => 'Updated description',
        'order' => $task->order,
        'priority' => 'high',
        'status' => 'in_progress',
        'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
        'assigned_user_id' => $task->assigned_user_id,
    ];

    $this->put(route('tasks.update', $task), $data)
        ->assertRedirect(route('boards.show', $this->board));

    $this->assertDatabaseHas('tasks', ['title' => 'Updated Task']);
});

test('a user can delete a task', function () {
    $task = Task::factory()->create(['board_id' => $this->board->id]);

    $this->delete(route('tasks.destroy', $task))
        ->assertRedirect(route('boards.show', $this->board));

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});
