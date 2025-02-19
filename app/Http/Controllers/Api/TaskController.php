<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(Request $request): Response
    {
        return response()->noContent(200);
    }

    public function update(Request $request, Task $task): Response
    {
        return response()->noContent(200);
    }

    public function reorder(Request $request): Response
    {
        return response()->noContent(200);
    }
}
