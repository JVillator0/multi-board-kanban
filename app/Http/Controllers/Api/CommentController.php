<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CommentIndexRequest;
use App\Http\Requests\Api\CommentStoreRequest;
use App\Http\Requests\Api\CommentUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function index(CommentIndexRequest $request): JsonResponse
    {
        $comments = Comment::query()
            ->with('user:id,name,email')
            ->where('task_id', $request->task_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(CommentResource::collection($comments));
    }

    public function store(CommentStoreRequest $request): JsonResponse
    {
        $comment = Comment::create([
            'task_id' => $request->task_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        $comment->loadMissing('user');

        return response()->json(CommentResource::make($comment), 201);
    }

    public function update(CommentUpdateRequest $request, Comment $comment): JsonResponse
    {
        $comment = tap($comment)->update($request->validated());

        $comment->loadMissing('user');

        return response()->json($comment);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(['message' => 'Comment deleted.'], 200);
    }
}
