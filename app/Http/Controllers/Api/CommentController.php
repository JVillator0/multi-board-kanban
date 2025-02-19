<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function index(Request $request): Response
    {
        return response()->noContent(201);
    }

    public function store(Request $request): Response
    {
        return response()->noContent(200);
    }

    public function update(Request $request, Comment $comment): Response
    {
        return response()->noContent(200);
    }

    public function destroy(Request $request, Comment $comment): Response
    {
        return response()->noContent();
    }
}
