<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TaskController extends Controller
{
    public function store(TaskStoreRequest $request): RedirectResponse
    {
        $task = Task::create($request->validated());

        return Redirect::route('boards.show', $task->board_id);
    }

    public function update(TaskUpdateRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return Redirect::route('boards.show', $task->board_id);
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $task->delete();

        return Redirect::route('boards.show', $task->board_id);
    }
}
