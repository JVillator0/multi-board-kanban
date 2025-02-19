<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Task;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\TaskController
 */
final class TaskControllerTest extends TestCase
{
    #[Test]
    public function index_responds_with(): void
    {
        $response = $this->get(route('tasks.index'));

        $response->assertOk();
    }


    #[Test]
    public function update_responds_with(): void
    {
        $task = Task::factory()->create();

        $response = $this->put(route('tasks.update', $task));

        $response->assertOk();
    }


    #[Test]
    public function reorder_responds_with(): void
    {
        $response = $this->get(route('tasks.reorder'));

        $response->assertOk();
    }
}
