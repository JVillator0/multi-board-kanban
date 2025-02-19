<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskIndexRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(TaskIndexRequest $request): Response
    {
        $tasks = Task::where('board_id', $request->board_id)->orderBy('order')->get();

        return Inertia::render('Boards/Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }

    public function store(TaskStoreRequest $request): Response
    {
        $task = Task::create($request->validated());

        return Inertia::render('Boards/Tasks/Show', [
            'task' => $task,
        ]);
    }

    public function update(TaskUpdateRequest $request, Task $task): Response
    {
        $task = Task::find($task);

        $task->update($request->validated());

        return Inertia::render('Boards/Tasks/Show', [
            'task' => $task,
        ]);
    }

    public function destroy(Request $request, Task $task): Response
    {
        $task->delete();

        $tasks = Task::where('board_id', $task->board->id)->orderBy('order')->get();

        return Inertia::render('Boards/Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }
}
