<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BoardController
 */
final class BoardControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $boards = Board::factory()->count(3)->create();

        $response = $this->get(route('boards.index'));
    }


    #[Test]
    public function create_behaves_as_expected(): void
    {
        $response = $this->get(route('boards.create'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BoardController::class,
            'store',
            \App\Http\Requests\BoardStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $title = fake()->sentence(4);
        $description = fake()->text();

        $response = $this->post(route('boards.store'), [
            'title' => $title,
            'description' => $description,
        ]);

        $boards = Board::query()
            ->where('title', $title)
            ->where('description', $description)
            ->get();
        $this->assertCount(1, $boards);
        $board = $boards->first();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $board = Board::factory()->create();

        $response = $this->get(route('boards.show', $board));
    }


    #[Test]
    public function edit_behaves_as_expected(): void
    {
        $board = Board::factory()->create();

        $response = $this->get(route('boards.edit', $board));
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BoardController::class,
            'update',
            \App\Http\Requests\BoardUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $board = Board::factory()->create();
        $title = fake()->sentence(4);
        $description = fake()->text();
        $order = fake()->numberBetween(-10000, 10000);

        $response = $this->put(route('boards.update', $board), [
            'title' => $title,
            'description' => $description,
            'order' => $order,
        ]);

        $board->refresh();

        $this->assertEquals($title, $board->title);
        $this->assertEquals($description, $board->description);
        $this->assertEquals($order, $board->order);
    }


    #[Test]
    public function reorder_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BoardController::class,
            'reorder',
            \App\Http\Requests\BoardReorderRequest::class
        );
    }

    #[Test]
    public function reorder_behaves_as_expected(): void
    {
        $board = Board::factory()->create();
        $order = fake()->numberBetween(-10000, 10000);

        $response = $this->get(route('boards.reorder'), [
            'order' => $order,
        ]);

        $board->refresh();

        $this->assertEquals($order, $board->order);
    }


    #[Test]
    public function destroy_deletes(): void
    {
        $board = Board::factory()->create();
        $boards = Board::factory()->count(3)->create();

        $response = $this->delete(route('boards.destroy', $board));

        $this->assertModelMissing($board);
    }
}
