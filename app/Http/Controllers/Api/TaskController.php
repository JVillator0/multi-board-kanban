<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TaskIndexRequest;
use App\Http\Requests\Api\TaskReorderRequest;
use App\Http\Requests\Api\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(TaskIndexRequest $request): JsonResponse
    {
        $tasks = Task::query()
            ->where('board_id', $request->board_id)
            ->with([
                'assignedUser:id,name,email',
                'comments.user:id,name,email',
            ])
            ->get();

        return response()->json(TaskResource::collection($tasks));
    }

    public function update(TaskUpdateRequest $request, Task $task): JsonResponse
    {
        $task = tap($task)->update($request->validated());

        return response()->json(new TaskResource($task));
    }

    public function reorder(TaskReorderRequest $request): JsonResponse
    {
        $tasks = collect($request->tasks);

        $tasks->each(fn ($task) => Task::where('id', $task['id'])->update(['order' => $task['order']]));

        return response()->json(['message' => 'Tasks reordered successfully.']);
    }
}
