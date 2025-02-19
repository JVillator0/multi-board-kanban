<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardStoreRequest;
use App\Http\Requests\BoardUpdateRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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

    public function store(BoardStoreRequest $request): RedirectResponse
    {
        Board::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'order' => Board::where('user_id', auth()->id())->max('order') + 1,
        ]);

        return Redirect::route('boards.index');
    }

    public function show(Request $request, Board $board): Response
    {
        $board->load([
            'tasks.assignedUser:id,name,email',
            'tasks.comments.user:id,name,email',
            'invitations.guest:id,name,email',
        ]);

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

    public function update(BoardUpdateRequest $request, Board $board): RedirectResponse
    {
        $board->update([
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? $board->order,
        ]);

        return Redirect::route('boards.index');
    }

    public function destroy(Request $request, Board $board): RedirectResponse
    {
        $board->delete();

        return Redirect::route('boards.index');
    }
}
