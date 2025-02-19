<?php

use App\Models\Board;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('a user can see their boards', function () {
    Board::factory()->count(3)->create(['user_id' => $this->user->id]);

    $this->get(route('boards.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Boards/Index')
            ->has('boards', 3)
        );
});

test('a user can create a board', function () {
    $data = [
        'title' => 'New Board',
        'description' => 'Test description',
    ];

    $this->post(route('boards.store'), $data)
        ->assertRedirect(route('boards.index'));

    $this->assertDatabaseHas('boards', [
        'title' => 'New Board',
        'description' => 'Test description',
        'user_id' => $this->user->id,
    ]);
});

test('a user can update a board', function () {
    $board = Board::factory()->create(['user_id' => $this->user->id]);

    $data = ['title' => 'Updated Board'];

    $this->put(route('boards.update', $board), $data)
        ->assertRedirect(route('boards.index'));

    $this->assertDatabaseHas('boards', ['title' => 'Updated Board']);
});

test('a user can delete a board', function () {
    $board = Board::factory()->create(['user_id' => $this->user->id]);

    $this->delete(route('boards.destroy', $board))
        ->assertRedirect(route('boards.index'));

    $this->assertDatabaseMissing('boards', ['id' => $board->id]);
});
