<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationStoreRequest;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InvitationController extends Controller
{
    public function store(InvitationStoreRequest $request): Response
    {
        $invitation = Invitation::create($request->validated());

        $board->invitation->notify(new BoardInvitation($board));

        return Inertia::render('Boards/Invitation/Index', [
            'invitations' => $invitations,
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Boards/Invitation/Create');
    }

    public function destroy(Request $request, Invitation $invitation): Response
    {
        $invitation = Invitation::find($invitation);

        $invitation->delete();

        $invitations = Invitation::where('board_id', $board_id)->get();

        return Inertia::render('Boards/Invitation/Index', [
            'invitations' => $invitations,
        ]);
    }

    public function resend(Request $request)
    {
        $invitation = Invitation::find($invitation);

        $board->invitation->notify(new BoardInvitation($board));

        return $Boards/Invitation/Index with:invitations;
    }
}
