<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InvitationIndexRequest;
use App\Http\Requests\Api\InvitationStoreRequest;
use App\Http\Resources\InvitationResource;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\BoardInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class InvitationController extends Controller
{
    public function index(InvitationIndexRequest $request)
    {
        $records = Invitation::where('board_id', $request->board_id)->get([
            'id',
            'email',
            'status',
        ]);

        return response()->json(InvitationResource::collection($records));
    }

    public function store(InvitationStoreRequest $request)
    {
        $invitation = Invitation::firstOrCreate([
            'email' => $request->email,
            'board_id' => $request->board_id,
        ], [
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        if ($invitation->status === 'accepted') {
            return response()->json(['message' => 'Invitation already accepted'], 400);
        }

        $existingUser = User::where('email', $request->email)->value('id');

        Notification::route('mail', $request->email)->notify(new BoardInvitationNotification(
            $invitation,
            $existingUser !== null
        ));

        return response()->json(['message' => 'Invitation sent successfully']);
    }

    public function resend(Request $request, Invitation $invitation)
    {
        if ($invitation->status === 'accepted') {
            return response()->json(['message' => 'Cannot resend an accepted invitation'], 400);
        }

        $existingUser = User::where('email', $invitation->email)->exists();

        $invitation->update(['status' => 'pending']);

        Notification::route('mail', $invitation->email)->notify(new BoardInvitationNotification(
            $invitation,
            $existingUser
        ));

        return response()->json(['message' => 'Invitation resent successfully']);
    }

    public function revoke(Request $request, Invitation $invitation)
    {
        $invitation->update(['status' => 'revoked']);

        return response()->json(['message' => 'Invitation revoked successfully']);
    }
}
