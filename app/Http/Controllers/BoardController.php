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

        $sharedBoards = Board::query()
            ->whereHas('invitations', fn ($query) => $query->where('email', auth()->user()->email)->where('status', 'accepted'))
            ->with('invitations.guest:id,name,email')
            ->get([
                'id',
                'title',
                'description',
                'order',
            ]);

        return Inertia::render('Boards/Index', [
            'boards' => BoardResource::collection($boards),
            'shared_boards' => BoardResource::collection($sharedBoards),
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

    public function show(Request $request, Board $board): Response | RedirectResponse
    {
        if ($board->user_id != auth()->id()) {
            $memberIds = $board->invitations->pluck('guest.id');
            if (! $memberIds->contains(auth()->id())) {
                return Redirect::route('boards.index');
            }
        }

        $board->load([
            'tasks.assignedUser:id,name,email',
            'tasks.comments.user:id,name,email',
            'invitations.guest:id,name,email',
        ]);

        return Inertia::render('Boards/Show', [
            'board' => BoardResource::make($board),
        ]);
    }

    public function edit(Request $request, Board $board): Response | RedirectResponse
    {
        if ($board->user_id != auth()->id()) {
            $memberIds = $board->invitations->pluck('guest.id');
            if (! $memberIds->contains(auth()->id())) {
                return Redirect::route('boards.index');
            }
        }

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

    public function invitations(Request $request, Board $board): Response
    {
        return Inertia::render('Boards/Invitations', [
            'board' => BoardResource::make($board),
        ]);
    }

    public function acceptInvitation(Request $request, Board $board): RedirectResponse
    {
        if ($board->user_id != auth()->id()) {
            $memberIds = $board->invitations->pluck('guest.id');
            if (! $memberIds->contains(auth()->id())) {
                return Redirect::route('boards.index');
            }
        }

        $board->invitations()
            ->where('email', auth()->user()->email)
            ->where('status', 'pending')
            ->first(['id', 'status'])
            ?->update(['status' => 'accepted']);

        return Redirect::route('boards.index');
    }

    public function destroy(Request $request, Board $board): RedirectResponse
    {
        $board->delete();

        return Redirect::route('boards.index');
    }
}
