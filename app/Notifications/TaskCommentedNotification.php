<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class TaskCommentedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->comment->user->name} commented on task '{$this->comment->task->title}'.",
            'task_id' => $this->comment->task_id,
            'url' => route('boards.show', $this->comment->task->board_id),
        ];
    }
}
