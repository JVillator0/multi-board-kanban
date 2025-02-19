<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request): Response
    {
        $comment = Comment::create($request->validated());

        return Inertia::render('Boards/Tasks/Show', [
            'comment.task_id' => $comment.task_id,
        ]);
    }

    public function update(CommentUpdateRequest $request, Comment $comment): Response
    {
        $comment = Comment::find($comment);


        $comment->update($request->validated());

        return Inertia::render('Boards/Tasks/Show', [
            'comment.task_id' => $comment.task_id,
        ]);
    }

    public function destroy(Request $request, Comment $comment): Response
    {
        $comment = Comment::find($comment);

        $comment->delete();

        return Inertia::render('Boards/Tasks/Show', [
            'comment.task_id' => $comment.task_id,
        ]);
    }
}
