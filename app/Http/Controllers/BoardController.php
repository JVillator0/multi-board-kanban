<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardStoreRequest;
use App\Http\Requests\BoardUpdateRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BoardController extends Controller
{
    public function index(Request $request): Response
    {
        $boards = Board::query()
            ->where('user_id', auth()->id())
            ->with('invitations.guest:id,name,email')
            ->orderBy('order')
            ->get([
                'id',
                'title',
                'description',
                'order',
                'user_id',
            ]);

        return Inertia::render('Boards/Index', [
            'boards' => BoardResource::collection($boards),
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
            'board' => BoardResource::make($board),
        ]);
    }

    public function show(Request $request, Board $board): Response
    {
        return Inertia::render('Boards/Show', [
            'board' => BoardResource::make($board),
        ]);
    }

    public function edit(Request $request, Board $board): Response
    {
        return Inertia::render('Boards/Edit', [
            'board' => BoardResource::make($board),
        ]);
    }

    public function update(BoardUpdateRequest $request, Board $board): Response
    {
        $board = tap($board)->update($request->validated());

        return Inertia::render('Boards/Show', [
            'board' => BoardResource::make($board),
        ]);
    }

    public function destroy(Request $request, Board $board): Response
    {
        $board->delete();

        $boards = Board::where('user_id', auth()->id())->orderBy('order')->get();

        return Inertia::render('Boards/Index', [
            'boards' => BoardResource::collection($boards),
        ]);
    }
}
