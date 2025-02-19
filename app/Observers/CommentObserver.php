<?php

namespace App\Observers;

use App\Models\Comment;
use App\Notifications\TaskCommentedNotification;

class CommentObserver
{
    public function created(Comment $comment)
    {
        // Notify all guests of the board
        $comment->task->board->invitations->pluck('guest')->each(function ($user) use ($comment) {
            if ($user->id !== auth()->id()) {
                $user->notify(new TaskCommentedNotification($comment));
            }
        });

        // Notify the board owner
        if ($comment->task->board->user_id !== auth()->id()) {
            $comment->task->board->user->notify(new TaskCommentedNotification($comment));
        }
    }
}
