<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Board;
use App\Models\Id;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TaskController
 */
final class TaskControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'index',
            \App\Http\Requests\TaskIndexRequest::class
        );
    }

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $title = fake()->sentence(4);
        $order = fake()->numberBetween(-10000, 10000);
        $priority = fake()->randomElement(/** enum_attributes **/);
        $status = fake()->randomElement(/** enum_attributes **/);
        $board = Board::factory()->create();
        $user = User::factory()->create();
        $id = Id::factory()->create();
        $tasks = Task::factory()->count(3)->create();

        $response = $this->get(route('tasks.index'), [
            'title' => $title,
            'order' => $order,
            'priority' => $priority,
            'status' => $status,
            'board_id' => $board->id,
            'user_id' => $user->id,
            'id' => $id->id,
        ]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'store',
            \App\Http\Requests\TaskStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $title = fake()->sentence(4);
        $description = fake()->text();
        $order = fake()->numberBetween(-10000, 10000);
        $priority = fake()->randomElement(/** enum_attributes **/);
        $status = fake()->randomElement(/** enum_attributes **/);
        $board = Board::factory()->create();

        $response = $this->post(route('tasks.store'), [
            'title' => $title,
            'description' => $description,
            'order' => $order,
            'priority' => $priority,
            'status' => $status,
            'board_id' => $board->id,
        ]);

        $tasks = Task::query()
            ->where('title', $title)
            ->where('description', $description)
            ->where('order', $order)
            ->where('priority', $priority)
            ->where('status', $status)
            ->where('board_id', $board->id)
            ->get();
        $this->assertCount(1, $tasks);
        $task = $tasks->first();
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'update',
            \App\Http\Requests\TaskUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $task = Task::factory()->create();
        $title = fake()->sentence(4);
        $description = fake()->text();
        $order = fake()->numberBetween(-10000, 10000);
        $priority = fake()->randomElement(/** enum_attributes **/);
        $status = fake()->randomElement(/** enum_attributes **/);

        $response = $this->put(route('tasks.update', $task), [
            'title' => $title,
            'description' => $description,
            'order' => $order,
            'priority' => $priority,
            'status' => $status,
        ]);

        $task->refresh();

        $this->assertEquals($title, $task->title);
        $this->assertEquals($description, $task->description);
        $this->assertEquals($order, $task->order);
        $this->assertEquals($priority, $task->priority);
        $this->assertEquals($status, $task->status);
    }


    #[Test]
    public function reorder_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'reorder',
            \App\Http\Requests\TaskReorderRequest::class
        );
    }

    #[Test]
    public function reorder_behaves_as_expected(): void
    {
        $task = Task::factory()->create();
        $order = fake()->numberBetween(-10000, 10000);

        $response = $this->get(route('tasks.reorder'), [
            'order' => $order,
        ]);

        $task->refresh();

        $this->assertEquals($order, $task->order);
    }


    #[Test]
    public function destroy_deletes(): void
    {
        $task = Task::factory()->create();
        $tasks = Task::factory()->count(3)->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $this->assertModelMissing($task);
    }
}
