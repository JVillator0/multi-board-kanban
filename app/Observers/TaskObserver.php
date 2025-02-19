<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskUpdatedNotification;

class TaskObserver
{
    public function updated(Task $task)
    {
        if ($task->isDirty('status')) {
            // Notify all guests of the board
            $task->board->invitations->pluck('guest')->each(function (User $user) use ($task) {
                if ($user->id !== auth()->id()) {
                    $user->notify(new TaskUpdatedNotification($task));
                }
            });

            // Notify the board owner
            if ($task->board->user_id !== auth()->id()) {
                $task->board->user->notify(new TaskUpdatedNotification($task));
            }
        }
    }
}
