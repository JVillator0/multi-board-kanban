<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class BoardInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Invitation $invitation,
        public bool $userExists
    ) {
        //
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $acceptUrl = URL::temporarySignedRoute('boards.invitations.accept', now()->addDay(), ['board' => $this->invitation->board_id]);

        $registerUrl = route('register', ['email' => $this->invitation->email]);

        $mail = (new MailMessage)
            ->subject('You have been invited to join a Board')
            ->greeting('Hello!')
            ->line("You have been invited to join the board: **{$this->invitation->board->title}**.");

        if ($this->userExists) {
            $mail->line('Click the button below to accept the invitation.')
                ->action('Accept Invitation', $acceptUrl);
        } else {
            $mail->line("It looks like you don't have an account. Please register first to accept the invitation.")
                ->action('Register', $registerUrl);
        }

        return $mail->line('If you do not wish to accept this invitation, you can simply ignore this email.');
    }

    public function toArray($notifiable)
    {
        if (! $this->userExists) {
            return [];
        }

        return [
            'message' => "You have been invited to join the board: {$this->invitation->board->title}.",
            'type' => 'board_invitation',
            'data' => [
                'board_title' => $this->invitation->board->title,
                'board_id' => $this->invitation->board->id,
                'invitation_id' => $this->invitation->id,
                'user_exists' => $this->userExists,
            ],
        ];
    }
}
