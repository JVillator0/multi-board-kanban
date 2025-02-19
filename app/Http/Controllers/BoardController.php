<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardReorderRequest;
use App\Http\Requests\BoardStoreRequest;
use App\Http\Requests\BoardUpdateRequest;
use App\Models\Board;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BoardController extends Controller
{
    public function index(Request $request): Response
    {
        $boards = Board::where('user_id', $user_id)->orderBy('order')->get();

        return Inertia::render('Boards/Index', [
            'boards' => $boards,
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Boards/Create');
    }

    public function store(BoardStoreRequest $request): Response
    {
        $board = Board::create($request->validated());

        return Inertia::render('Boards/Show', [
            'board' => $board,
        ]);
    }

    public function show(Request $request, Board $board): Response
    {
        $board = Board::find($board);

        return Inertia::render('Boards/Show', [
            'board' => $board,
        ]);
    }

    public function edit(Request $request, Board $board): Response
    {
        $board = Board::find($board);

        return Inertia::render('Boards/Edit', [
            'board' => $board,
        ]);
    }

    public function update(BoardUpdateRequest $request, Board $board): Response
    {
        $board = Board::find($board);

        $board->update($request->validated());

        return Inertia::render('Boards/Show', [
            'board' => $board,
        ]);
    }

    public function reorder(BoardReorderRequest $request): Response
    {
        $board = Board::find($board);

        $board->update($request->validated());

        return Inertia::render('Boards/Index', [
            'boards' => $boards,
        ]);
    }

    public function destroy(Request $request, Board $board): Response
    {
        $board = Board::find($board);

        $board->delete();

        $boards = Board::where('user_id', $user_id)->orderBy('order')->get();

        return Inertia::render('Boards/Index', [
            'boards' => $boards,
        ]);
    }
}
