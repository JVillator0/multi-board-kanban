<?php

namespace App\Listeners;

use App\Models\Invitation;
use Illuminate\Auth\Events\Registered as EventsRegistered;

class AcceptInvitationAfterRegistration
{
    public function __construct()
    {
        //
    }

    public function handle(EventsRegistered $event): void
    {
        Invitation::query()
            ->where('email', $event->user->email)
            ->where('status', 'pending')
            ->get(['id', 'status'])
            ->each(fn (Invitation $invitation) => $invitation->update(['status' => 'accepted']));
    }
}
